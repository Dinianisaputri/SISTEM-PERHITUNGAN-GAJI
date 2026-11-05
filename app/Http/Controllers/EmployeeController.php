<?php
namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\WarningLetter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\Region;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class EmployeeController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | DAFTAR BAGIAN
    |--------------------------------------------------------------------------
    */
private function jabatanList()
{
    return DB::table('master_jabatans')
        ->orderBy('nama')
        ->pluck('nama')
        ->toArray();
}
private function bagianList()
{
    return DB::table('master_bagians')
        ->orderBy('nama')
        ->pluck('nama')
        ->toArray();
}
private function penempatanList()
{
    return DB::table('master_penempatans')
        ->orderBy('nama')
        ->pluck('nama')
        ->toArray();
}

private function syncBpjsRegistrationFlags(array &$validated): void
{
    $validated['bpjs_tk_terdaftar'] =
        filled($validated['program_bpjs_tk'] ?? null) &&
        filled($validated['no_bpjs_tk'] ?? null);
    $validated['bpjs_kesehatan_terdaftar'] =
        filled($validated['kelas_bpjs_kesehatan'] ?? null) &&
        filled($validated['no_bpjs_kesehatan'] ?? null);
}

    /*
    |--------------------------------------------------------------------------
    | INDEX DATA KARYAWAN
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Employee::query();
        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere(
                        'no_induk_karyawan',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'no_ktp',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'fingerprint_pin',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'bagian',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'alamat_domisili',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhereHas(
                            'provinsi',
                            function ($region) use ($search) {
                                $region->where(
                                    'nama',
                                    'like',
                                    "%{$search}%"
                                );
                            }
                        )
                        ->orWhereHas(
                            'kabupaten',
                            function ($region) use ($search) {
                                $region->where(
                                    'nama',
                                    'like',
                                    "%{$search}%"
                                );
                            }
                        )
                        ->orWhereHas(
                            'kecamatan',
                            function ($region) use ($search) {
                                $region->where(
                                    'nama',
                                    'like',
                                    "%{$search}%"
                                );
                            }
                        )
                        ->orWhereHas(
                            'desa',
                            function ($region) use ($search) {
                                $region->where(
                                    'nama',
                                    'like',
                                    "%{$search}%"
                                );
                            }
                        )
                                            ->orWhere(
                                                'alamat_domisili',
                                                'like',
                                                "%{$search}%"
                                            );
                                    });
                                }
        /*
        |--------------------------------------------------------------------------
        | FILTER BAGIAN
        |--------------------------------------------------------------------------
        */
        if ($request->filled('bagian')) {
            $query->where(
                'bagian',
                $request->bagian
            );
        }
        /*
        |--------------------------------------------------------------------------
        | FILTER JENIS KARYAWAN
        |--------------------------------------------------------------------------
        */
        if ($request->filled('jenis_karyawan')) {
            $query->where(
                'jenis_karyawan',
                $request->jenis_karyawan
            );
        }
        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {
            $query->where(
                'is_active',
                $request->status === 'aktif'
            );
        }
        if ($request->input('kontrak') === 'akan-berakhir') {
            $query->where('is_active', true)
                ->whereNotNull('selesai_kontrak')
                ->whereBetween('selesai_kontrak', [
                    now()->toDateString(),
                    now()->addDays(30)->toDateString(),
                ]);
        }
        if ($request->input('sp') === 'ada') {
            $query->where('jumlah_sp', '>', 0);
        }
        /*
        |--------------------------------------------------------------------------
        | DATA KARYAWAN
        |--------------------------------------------------------------------------
        */
        $employees = $query
            ->orderByRaw('CAST(fingerprint_pin AS UNSIGNED) ASC')
            ->paginate(100)
            ->withQueryString();
        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */
        $totalKaryawan = Employee::count();
        $karyawanAktif = Employee::where(
            'is_active',
            true
        )->count();
        $karyawanNonaktif = Employee::where(
            'is_active',
            false
        )->count();
        $karyawanBorongan = Employee::where(
            'jenis_karyawan',
            'Borongan'
        )->count();
        /*
        |--------------------------------------------------------------------------
        | KONTRAK AKAN BERAKHIR
        |--------------------------------------------------------------------------
        */
        $kontrakAkanBerakhir = Employee::where('is_active', true)
            ->whereNotNull('selesai_kontrak')
            ->whereBetween('selesai_kontrak', [
                now()->toDateString(),
                now()->addDays(30)->toDateString(),
            ])
            ->count();
        /*
        |--------------------------------------------------------------------------
        | TOTAL SP
        |--------------------------------------------------------------------------
        */
        $totalSP = WarningLetter::count();
        /*
        |--------------------------------------------------------------------------
        | LIST BAGIAN
        |--------------------------------------------------------------------------
        */
        $bagianList = $this->bagianList();
        $jabatanList = $this->jabatanList();
        return view(
            'employees.index',
            compact(
                'employees',
                'totalKaryawan',
                'karyawanAktif',
                'karyawanNonaktif',
                'karyawanBorongan',
                'kontrakAkanBerakhir',
                'totalSP',
                'bagianList',
                'jabatanList'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH KARYAWAN
    |--------------------------------------------------------------------------
    */
   public function create()
{
    $bagianList = $this->bagianList();
    $jabatanList = $this->jabatanList();
    $penempatanList = $this->penempatanList();
    $provinsiList = Region::where('level', 'provinsi')
        ->orderBy('nama')
        ->get();
    return view(
        'employees.create',
        compact(
            'bagianList',
            'jabatanList',
            'penempatanList',
            'provinsiList'
        )
    );
}

    /*
    |--------------------------------------------------------------------------
    | SIMPAN KARYAWAN
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            /*
            |--------------------------------------------------------------------------
            | FINGERPRINT
            |--------------------------------------------------------------------------
            */
            'fingerprint_pin' => [
                'nullable',
                'string',
                'max:50',
                'unique:employees,fingerprint_pin',
            ],
            /*
            |--------------------------------------------------------------------------
            | NO INDUK KARYAWAN
            |--------------------------------------------------------------------------
            */
            'no_induk_karyawan' => [
                'nullable',
                'string',
                'max:50',
                'unique:employees,no_induk_karyawan',
            ],
            /*
            |--------------------------------------------------------------------------
            | NAMA
            |--------------------------------------------------------------------------
            */
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            /*
            |--------------------------------------------------------------------------
            | BAGIAN
            |--------------------------------------------------------------------------
            */
           'bagian' => [
    'required',
    'string',
    'max:255',
    Rule::in($this->bagianList()),
],
'jabatan' => [
    'nullable',
    'string',
    'max:255',
    Rule::in($this->jabatanList()),
],
'penempatan' => [
    'nullable',
    'string',
    'max:255',
    Rule::in($this->penempatanList()),
],
            /*
            |--------------------------------------------------------------------------
            | JENIS KARYAWAN
            |--------------------------------------------------------------------------
            */
            'jenis_karyawan' => [
                'nullable',
                'string',
                'max:50',
                Rule::in([
                    'Borongan',
                    'Bulanan',
                    'Harian',
                ]),
            ],
            /*
            |--------------------------------------------------------------------------
            | PENDIDIKAN
            |--------------------------------------------------------------------------
            */
            'pendidikan' => [
                'nullable',
                'string',
                'max:255',
            ],
            /*
            |--------------------------------------------------------------------------
            | STATUS PERNIKAHAN
            |--------------------------------------------------------------------------
            */
            'status_pernikahan' => [
                'nullable',
                Rule::in([
                    'Belum Menikah',
                    'Sudah Menikah',
                ]),
            ],
            /*
            |--------------------------------------------------------------------------
            | JENIS KELAMIN
            |--------------------------------------------------------------------------
            */
            'jenis_kelamin' => [
                'nullable',
                Rule::in([
                    'Laki-laki',
                    'Perempuan',
                ]),
            ],
            /*
            |--------------------------------------------------------------------------
            | TEMPAT LAHIR
            |--------------------------------------------------------------------------
            */
            'tempat_lahir' => [
                'nullable',
                'string',
                'max:255',
            ],
            /*
            |--------------------------------------------------------------------------
            | TANGGAL LAHIR
            |--------------------------------------------------------------------------
            */
            'tanggal_lahir' => [
                'nullable',
                'date',
            ],
            /*
            |--------------------------------------------------------------------------
            | ALAMAT
            |--------------------------------------------------------------------------
            */
            'alamat_domisili' => [
                'nullable',
                'string',
            ],
                        'provinsi_id' => [
                'nullable',
                'exists:regions,id',
            ],
            'kabupaten_id' => [
                'nullable',
                'exists:regions,id',
            ],
            'kecamatan_id' => [
                'nullable',
                'exists:regions,id',
            ],
            'desa_id' => [
                'nullable',
                'exists:regions,id',
            ],
            'rt' => [
                'nullable',
                'string',
                'max:10',
            ],
            'rw' => [
                'nullable',
                'string',
                'max:10',
            ],
            'detail_alamat' => [
                'nullable',
                'string',
            ],
            /*
            |--------------------------------------------------------------------------
            | NO KTP
            |--------------------------------------------------------------------------
            */
            'no_ktp' => [
                'nullable',
                'string',
                'max:50',
                'unique:employees,no_ktp',
            ],
            /*
            |--------------------------------------------------------------------------
            | MULAI KERJA
            |--------------------------------------------------------------------------
            */
            'mulai_kerja' => [
                'nullable',
                'date',
            ],
            /*
            |--------------------------------------------------------------------------
            | TANGGAL KONTRAK
            |--------------------------------------------------------------------------
            */
            'tanggal_kontrak' => [
                'nullable',
                'date',
            ],
            /*
            |--------------------------------------------------------------------------
            | SELESAI KONTRAK
            |--------------------------------------------------------------------------
            */
            'selesai_kontrak' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_kontrak',
            ],
            /*
            |--------------------------------------------------------------------------
            | KONTRAK KE
            |--------------------------------------------------------------------------
            */
            'kontrak_ke' => [
                'nullable',
                'integer',
                'min:1',
            ],
            /*
            |--------------------------------------------------------------------------
            | PAS FOTO
            |--------------------------------------------------------------------------
            */
            'pas_foto' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
            /*
            |--------------------------------------------------------------------------
            | FOTO KK
            |--------------------------------------------------------------------------
            */
            'foto_kk' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],
            /*
            |--------------------------------------------------------------------------
            | FOTO KTP
            |--------------------------------------------------------------------------
            */
            'foto_ktp' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],
            /*
            |--------------------------------------------------------------------------
            | FOTO BIODATA
            |--------------------------------------------------------------------------
            */
            'foto_biodata' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],
            /*
            |--------------------------------------------------------------------------
            | SURAT KONTRAK
            |--------------------------------------------------------------------------
            */
            'surat_kontrak' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],
            /*
            |--------------------------------------------------------------------------
            | JUMLAH SP
            |--------------------------------------------------------------------------
            */
            'jumlah_sp' => [
                'nullable',
                'integer',
                'min:0',
            ],
            /*
            |--------------------------------------------------------------------------
            | STATUS KARYAWAN
            |--------------------------------------------------------------------------
            */
            'is_active' => [
                'nullable',
                'boolean',
            ],
            'no_rekening' => [
    'nullable',
    'string',
    'max:50',
],
'npwp' => ['nullable', 'string', 'max:30'],

'no_bpjs_tk' => ['nullable', 'string', 'max:50'],
'program_bpjs_tk' => [
    'nullable',
    Rule::in(['Full Program', '2 Program']),
],

'no_bpjs_kesehatan' => ['nullable', 'string', 'max:50'],
'kelas_bpjs_kesehatan' => [
    'nullable',
    Rule::in(['Kelas 1', 'Kelas 2']),
],
        ]);
        /*
        |--------------------------------------------------------------------------
        | UPLOAD FILE
        |--------------------------------------------------------------------------
        */
$this->syncBpjsRegistrationFlags($validated);

foreach (
            [
                'pas_foto',
                'foto_kk',
                'foto_ktp',
                'foto_biodata',
                'surat_kontrak',
            ] as $field
        ) {
            if ($request->hasFile($field)) {
                $validated[$field] =
                    $request
                        ->file($field)
                        ->store(
                            'employees',
                            'public'
                        );
            }
        }
        /*
        |--------------------------------------------------------------------------
        | DEFAULT VALUE
        |--------------------------------------------------------------------------
        */
        if (empty($validated['no_induk_karyawan']) && $request->filled('nik')) {
            $validated['no_induk_karyawan'] = $request->input('nik');
        }
        $validated['is_active'] =
            $request->boolean('is_active');
        $validated['jumlah_sp'] =
            $request->input(
                'jumlah_sp',
                0
            );
        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */
        Employee::create($validated);
        return redirect()
            ->route('employees.index')
            ->with(
                'success',
                'Data karyawan berhasil ditambahkan.'
            );
    }
public function getRegions(Request $request)
{
    $level = $request->input('level');
    $parentId = $request->input('parent_id');
    $allowedLevels = [
        'kabupaten',
        'kecamatan',
        'desa',
    ];
    if (!in_array($level, $allowedLevels, true)) {
        return response()->json([]);
    }
    if (!$parentId) {
        return response()->json([]);
    }
    $regions = Region::where('level', $level)
        ->where('parent_id', $parentId)
        ->orderBy('nama')
        ->get([
            'id',
            'kode',
            'nama',
        ]);
    return response()->json($regions);
}

    /*
    |--------------------------------------------------------------------------
    | DETAIL KARYAWAN
    |--------------------------------------------------------------------------
    */
  public function show(Employee $employee)
{
    $employee->load([
        'contracts' => function ($query) {
            $query->orderBy('tanggal_mulai', 'asc')
                  ->orderBy('id', 'asc');
        },
        'warningLetters' => function ($query) {
            $query->orderBy('tanggal_surat', 'desc')
                  ->orderBy('id', 'desc');
        },
        'warningNotices' => function ($query) {
            $query->orderBy('tanggal_surat', 'desc')
                  ->orderBy('id', 'desc');
        },
    ]);
    return view('employees.show', compact('employee'));
}

    /*
    |--------------------------------------------------------------------------
    | FORM EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Employee $employee)
{
    $bagianList = $this->bagianList();
    $jabatanList = $this->jabatanList();
   $penempatanList = array_values(array_unique(array_filter(
    array_merge(
        $this->penempatanList(),
        [$employee->penempatan]
    ),
    fn ($item) => $item !== null && $item !== ''
)));
    $provinsiList = Region::where('level', 'provinsi')
        ->orderBy('nama')
        ->get();
    $kabupatenList = collect();
    $kecamatanList = collect();
    $desaList = collect();
    if ($employee->provinsi_id) {
        $kabupatenList = Region::where('level', 'kabupaten')
            ->where('parent_id', $employee->provinsi_id)
            ->orderBy('nama')
            ->get();
    }
    if ($employee->kabupaten_id) {
        $kecamatanList = Region::where('level', 'kecamatan')
            ->where('parent_id', $employee->kabupaten_id)
            ->orderBy('nama')
            ->get();
    }
    if ($employee->kecamatan_id) {
        $desaList = Region::where('level', 'desa')
            ->where('parent_id', $employee->kecamatan_id)
            ->orderBy('nama')
            ->get();
    }
    return view(
        'employees.edit',
        compact(
            'employee',
            'bagianList',
            'jabatanList',
            'penempatanList',
            'provinsiList',
            'kabupatenList',
            'kecamatanList',
            'desaList'
        )
    );
}

    /*
    |--------------------------------------------------------------------------
    | UPDATE KARYAWAN
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Employee $employee
    ) {
        $validated = $request->validate([
            /*
            |--------------------------------------------------------------------------
            | FINGERPRINT
            |--------------------------------------------------------------------------
            */
            'fingerprint_pin' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique(
                    'employees',
                    'fingerprint_pin'
                )->ignore($employee->id),
            ],
'no_rekening' => [
    'nullable',
    'string',
    'max:50',
],
'npwp' => ['nullable', 'string', 'max:30'],

'no_bpjs_tk' => ['nullable', 'string', 'max:50'],
'program_bpjs_tk' => [
    'nullable',
    Rule::in(['Full Program', '2 Program']),
],

'no_bpjs_kesehatan' => ['nullable', 'string', 'max:50'],
'kelas_bpjs_kesehatan' => [
    'nullable',
    Rule::in(['Kelas 1', 'Kelas 2']),
],
            'no_induk_karyawan' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique(
                    'employees',
                    'no_induk_karyawan'
                )->ignore($employee->id),
            ],
            /*
            |--------------------------------------------------------------------------
            | NAMA
            |--------------------------------------------------------------------------
            */
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            /*
            |--------------------------------------------------------------------------
            | BAGIAN
            |--------------------------------------------------------------------------
       'bagian' => [
    'required',
    'string',
    'max:255',
    Rule::in(array_values(array_unique(array_merge(
        $this->bagianList(),
        [$employee->bagian]
    )))),
],
'jabatan' => [
    'nullable',
    'string',
    'max:255',
    Rule::in(array_values(array_unique(array_merge(
        $this->jabatanList(),
        [$employee->jabatan]
    )))),
],
'penempatan' => [
    'nullable',
    'string',
    'max:255',
    Rule::in(array_values(array_unique(array_merge(
        $this->penempatanList(),
        [$employee->penempatan]
    )))),
],
            |--------------------------------------------------------------------------
            | JENIS KARYAWAN
            |--------------------------------------------------------------------------
            */
'jenis_karyawan' => [
    'nullable',
    'string',
    'max:50',
    Rule::in([
        'Borongan',
        'Bulanan',
        'Harian',
    ]),
],
            /*
            |--------------------------------------------------------------------------
            | PENDIDIKAN
            |--------------------------------------------------------------------------
            */
            'pendidikan' => [
                'nullable',
                'string',
                'max:255',
            ],
            /*
            |--------------------------------------------------------------------------
            | STATUS PERNIKAHAN
            |--------------------------------------------------------------------------
            */
            'status_pernikahan' => [
                'nullable',
                Rule::in([
                    'Belum Menikah',
                    'Sudah Menikah',
                ]),
            ],
            /*
            |--------------------------------------------------------------------------
            | JENIS KELAMIN
            |--------------------------------------------------------------------------
            */
            'jenis_kelamin' => [
                'nullable',
                Rule::in([
                    'Laki-laki',
                    'Perempuan',
                ]),
            ],
            /*
            |--------------------------------------------------------------------------
            | TEMPAT LAHIR
            |--------------------------------------------------------------------------
            */
            'tempat_lahir' => [
                'nullable',
                'string',
                'max:255',
            ],
            /*
            |--------------------------------------------------------------------------
            | TANGGAL LAHIR
            |--------------------------------------------------------------------------
            */
            'tanggal_lahir' => [
                'nullable',
                'date',
            ],
            /*
            |--------------------------------------------------------------------------
            | ALAMAT
            |--------------------------------------------------------------------------
            */
            'alamat_domisili' => [
                'nullable',
                'string',
            ],
                'provinsi_id' => [
                'nullable',
                'exists:regions,id',
            ],
            'kabupaten_id' => [
                'nullable',
                'exists:regions,id',
            ],
            'kecamatan_id' => [
                'nullable',
                'exists:regions,id',
            ],
            'desa_id' => [
                'nullable',
                'exists:regions,id',
            ],
            'rt' => [
                'nullable',
                'string',
                'max:10',
            ],
            'rw' => [
                'nullable',
                'string',
                'max:10',
            ],
            'detail_alamat' => [
                'nullable',
                'string',
            ],
            /*
            |--------------------------------------------------------------------------
            | NO KTP
            |--------------------------------------------------------------------------
            */
            'no_ktp' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique(
                    'employees',
                    'no_ktp'
                )->ignore($employee->id),
            ],
            /*
            |--------------------------------------------------------------------------
            | MULAI KERJA
            |--------------------------------------------------------------------------
            */
            'mulai_kerja' => [
                'nullable',
                'date',
            ],
            /*
            |--------------------------------------------------------------------------
            | TANGGAL KONTRAK
            |--------------------------------------------------------------------------
            */
            'tanggal_kontrak' => [
                'nullable',
                'date',
            ],
            /*
            |--------------------------------------------------------------------------
            | SELESAI KONTRAK
            |--------------------------------------------------------------------------
            */
            'selesai_kontrak' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_kontrak',
            ],
            /*
            |--------------------------------------------------------------------------
            | KONTRAK KE
            |--------------------------------------------------------------------------
            */
            'kontrak_ke' => [
                'nullable',
                'integer',
                'min:1',
            ],
            /*
            |--------------------------------------------------------------------------
            | PAS FOTO
            |--------------------------------------------------------------------------
            */
            'pas_foto' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
            /*
            |--------------------------------------------------------------------------
            | FOTO KK
            |--------------------------------------------------------------------------
            */
            'foto_kk' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],
            /*
            |--------------------------------------------------------------------------
            | FOTO KTP
            |--------------------------------------------------------------------------
            */
            'foto_ktp' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],
            /*
            |--------------------------------------------------------------------------
            | FOTO BIODATA
            |--------------------------------------------------------------------------
            */
            'foto_biodata' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],
            /*
            |--------------------------------------------------------------------------
            | SURAT KONTRAK
            |--------------------------------------------------------------------------
            */
            'surat_kontrak' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],
            /*
            |--------------------------------------------------------------------------
            | JUMLAH SP
            |--------------------------------------------------------------------------
            */
            'jumlah_sp' => [
                'nullable',
                'integer',
                'min:0',
            ],
            /*
            |--------------------------------------------------------------------------
            | STATUS KARYAWAN
            |--------------------------------------------------------------------------
            */
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);
        /*
        |--------------------------------------------------------------------------
        | UPDATE FILE
        |--------------------------------------------------------------------------
        */
        foreach (
            [
                'pas_foto',
                'foto_kk',
                'foto_ktp',
                'foto_biodata',
                'surat_kontrak',
            ] as $field
        ) {
            if ($request->hasFile($field)) {
                /*
                | Hapus file lama
                */
                if (
                    $employee->$field &&
                    Storage::disk('public')
                        ->exists($employee->$field)
                ) {
                    Storage::disk('public')
                        ->delete($employee->$field);
                }
                /*
                | Simpan file baru
                */
                $validated[$field] =
                    $request
                        ->file($field)
                        ->store(
                            'employees',
                            'public'
                        );
            }
        }
        /*
        |--------------------------------------------------------------------------
        | DEFAULT VALUE
        |--------------------------------------------------------------------------
        */
        $validated['is_active'] =
            $request->boolean('is_active');
        $validated['jumlah_sp'] =
            $request->input(
                'jumlah_sp',
                0
            );
        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA
        |--------------------------------------------------------------------------
        */
        // Pastikan penempatan dari form ikut tersimpan.
        $validated['penempatan'] = $request->input('penempatan');
        $this->syncBpjsRegistrationFlags($validated);
        $employee->update($validated);
        return redirect()
            ->route('employees.index')
            ->with(
                'success',
                'Data karyawan berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS KARYAWAN
    |--------------------------------------------------------------------------
    */

    public function destroy(Employee $employee)
    {
        /*
        |--------------------------------------------------------------------------
        | HAPUS FILE
        |--------------------------------------------------------------------------
        */
        foreach (
            [
                'pas_foto',
                'foto_kk',
                'foto_ktp',
                'foto_biodata',
                'surat_kontrak',
            ] as $field
        ) {
            if (
                $employee->$field &&
                Storage::disk('public')
                    ->exists($employee->$field)
            ) {
                Storage::disk('public')
                    ->delete($employee->$field);
            }
        }
        /*
        |--------------------------------------------------------------------------
        | HAPUS DATA
        |--------------------------------------------------------------------------
        */
        $employee->delete();
        return redirect()
            ->route('employees.index')
            ->with(
                'success',
                'Data karyawan berhasil dihapus.'
            );
    }
public function dashboard()
{
    $totalKaryawan = Employee::count();
    $karyawanAktif = Employee::where('is_active', true)->count();
    $awalPeriodeKontrak = now()->toDateString();
    $akhirPeriodeKontrak = now()->addDays(30)->toDateString();
    $kontrakSegeraBerakhirQuery = Employee::query()
        ->where('is_active', true)
        ->whereNotNull('selesai_kontrak')
        ->whereBetween('selesai_kontrak', [$awalPeriodeKontrak, $akhirPeriodeKontrak]);
    $kontrakAkanBerakhir = (clone $kontrakSegeraBerakhirQuery)->count();
    $kontrakSegeraBerakhir = (clone $kontrakSegeraBerakhirQuery)
        ->orderBy('selesai_kontrak')
        ->limit(5)
        ->get([
            'id',
            'name',
            'no_induk_karyawan',
            'bagian',
            'selesai_kontrak',
        ]);
    $karyawanTerbaru = Employee::query()
        ->whereNotNull('mulai_kerja')
        ->orderByDesc('mulai_kerja')
        ->orderByDesc('id')
        ->limit(5)
        ->get([
            'id',
            'name',
            'no_induk_karyawan',
            'bagian',
            'jabatan',
            'jenis_karyawan',
            'mulai_kerja',
        ]);
    $karyawanPerBagian = Employee::query()
        ->whereNotNull('bagian')
        ->where('bagian', '<>', '')
        ->select('bagian', DB::raw('COUNT(*) as total'))
        ->groupBy('bagian')
        ->orderByDesc('total')
        ->orderBy('bagian')
        ->limit(6)
        ->get();
    $totalKaryawanDenganBagian = Employee::query()
        ->whereNotNull('bagian')
        ->where('bagian', '<>', '')
        ->count();
    $karyawanPerJenis = Employee::query()
        ->whereNotNull('jenis_karyawan')
        ->where('jenis_karyawan', '<>', '')
        ->select('jenis_karyawan', DB::raw('COUNT(*) as total'))
        ->groupBy('jenis_karyawan')
        ->orderByDesc('total')
        ->orderBy('jenis_karyawan')
        ->get();
    $totalSP = WarningLetter::count();
    return view('employees.dashboard', compact(
        'totalKaryawan',
        'karyawanAktif',
        'kontrakAkanBerakhir',
        'kontrakSegeraBerakhir',
        'karyawanTerbaru',
        'karyawanPerBagian',
        'totalKaryawanDenganBagian',
        'karyawanPerJenis',
        'totalSP'
    ));
}
public function exportPdf(Request $request)
{
    $query = Employee::query()
        ->with([
            'provinsi',
            'kabupaten',
            'kecamatan',
            'desa',
        ]);
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('no_induk_karyawan', 'like', "%{$search}%")
                ->orWhere('no_ktp', 'like', "%{$search}%")
                ->orWhere('fingerprint_pin', 'like', "%{$search}%")
                ->orWhere('bagian', 'like', "%{$search}%")
                ->orWhere('jabatan', 'like', "%{$search}%")
                ->orWhere('penempatan', 'like', "%{$search}%")
                ->orWhere('alamat_domisili', 'like', "%{$search}%")
                ->orWhereHas('provinsi', function ($region) use ($search) {
                    $region->where('nama', 'like', "%{$search}%");
                })
                ->orWhereHas('kabupaten', function ($region) use ($search) {
                    $region->where('nama', 'like', "%{$search}%");
                })
                ->orWhereHas('kecamatan', function ($region) use ($search) {
                    $region->where('nama', 'like', "%{$search}%");
                })
                ->orWhereHas('desa', function ($region) use ($search) {
                    $region->where('nama', 'like', "%{$search}%");
                });
        });
    }
    foreach ([
        'bagian',
        'jabatan',
        'penempatan',
        'jenis_karyawan',
    ] as $field) {
        if ($request->filled($field)) {
            $query->where($field, $request->input($field));
        }
    }
    if ($request->filled('status')) {
        if ($request->status === 'aktif') {
            $query->where('is_active', true);
        } elseif ($request->status === 'nonaktif') {
            $query->where('is_active', false);
        }
    }
    foreach ([
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'desa_id',
    ] as $field) {
        if ($request->filled($field)) {
            $query->where($field, $request->input($field));
        }
    }
    $employees = $query
        ->orderByRaw('CAST(fingerprint_pin AS UNSIGNED) ASC')
        ->get();
    $filters = $request->only([
        'search',
        'bagian',
        'jabatan',
        'penempatan',
        'jenis_karyawan',
        'status',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'desa_id',
    ]);
    $pdf = Pdf::loadView('employees.pdf', [
        'employees' => $employees,
        'filters' => $filters,
        'printedAt' => now(),
    ])->setPaper('a4', 'portrait');
    return $pdf->download('data-karyawan-' . now()->format('Ymd-His') . '.pdf');
}
}
