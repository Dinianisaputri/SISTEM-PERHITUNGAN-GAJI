<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR BAGIAN
    |--------------------------------------------------------------------------
    */
    private function bagianList()
    {
        return [
            'OFFICE',
            'SECURITY',
            'TEKNISI',
            'SIPIL',
            'PENERIMAAN',
            'PRD M2-6',
            'PACKING',
            'CS',
            'PEST CONTROL',
            'BOILER',
            'DAPUR',
            'VALUE ADDED',
            'BULANAN FM',
            'DRIVER',
            'LIMBAH',
            'PRD M1',
            'SPV PK',
            'FROZEN FISH',
            'TEAM FM',
            'PRODUKSI'
        ];
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
        /*
|--------------------------------------------------------------------------
| FILTER KONTRAK AKAN BERAKHIR
|--------------------------------------------------------------------------
*/
if (
    $request->filled('kontrak') &&
    $request->kontrak === 'akan-berakhir'
) {
    $query->whereNotNull('selesai_kontrak')
        ->whereBetween('selesai_kontrak', [
            now()->startOfDay(),
            now()->addDays(30)->endOfDay(),
        ]);
}


/*
|--------------------------------------------------------------------------
| FILTER KARYAWAN YANG MEMILIKI SP
|--------------------------------------------------------------------------
*/
if (
    $request->filled('sp') &&
    $request->sp === 'ada'
) {
    $query->where('jumlah_sp', '>', 0);
}
        if ($request->filled('status')) {

            $query->where(
                'is_active',
                $request->status === 'aktif'
            );
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
        $kontrakAkanBerakhir = Employee::whereNotNull(
            'selesai_kontrak'
        )
            ->whereBetween(
                'selesai_kontrak',
                [
                    now()->startOfDay(),
                    now()->addDays(30)->endOfDay(),
                ]
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL SP
        |--------------------------------------------------------------------------
        */
        $totalSP = Employee::sum('jumlah_sp');


        /*
        |--------------------------------------------------------------------------
        | LIST BAGIAN
        |--------------------------------------------------------------------------
        */
        $bagianList = $this->bagianList();


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
                'bagianList'
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

        return view(
            'employees.create',
            compact('bagianList')
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


            /*
            |--------------------------------------------------------------------------
            | JABATAN
            |--------------------------------------------------------------------------
            */
            'jabatan' => [
                'nullable',
                'string',
                'max:255',
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
                Rule::in(['Borongan', 'Bulanan', 'Harian']),
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
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPLOAD FILE
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


    /*
    |--------------------------------------------------------------------------
    | DETAIL KARYAWAN
    |--------------------------------------------------------------------------
    */
   public function show(Employee $employee)
{
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

        return view(
            'employees.edit',
            compact(
                'employee',
                'bagianList'
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


            /*
            |--------------------------------------------------------------------------
            | NO INDUK KARYAWAN
            |--------------------------------------------------------------------------
            */
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
            */
            'bagian' => [
                'required',
                'string',
                'max:255',
                Rule::in($this->bagianList()),
            ],


            /*
            |--------------------------------------------------------------------------
            | JABATAN
            |--------------------------------------------------------------------------
            */
            'jabatan' => [
                'nullable',
                'string',
                'max:255',
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
                Rule::in(['Borongan', 'Bulanan', 'Harian']),
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

    $karyawanNonaktif = Employee::where('is_active', false)->count();

    $karyawanBorongan = Employee::where('jenis_karyawan', 'Borongan')->count();

    $karyawanBulanan = Employee::where('jenis_karyawan', 'Bulanan')->count();

    $karyawanHarian = Employee::where('jenis_karyawan', 'Harian')->count();

    $kontrakAkanBerakhir = Employee::whereNotNull('selesai_kontrak')
        ->whereBetween('selesai_kontrak', [
            now()->startOfDay(),
            now()->addDays(30)->endOfDay()
        ])
        ->count();

    $totalSP = Employee::sum('jumlah_sp');

    return view('employees.dashboard', compact(
        'totalKaryawan',
        'karyawanAktif',
        'karyawanNonaktif',
        'karyawanBorongan',
        'karyawanBulanan',
        'karyawanHarian',
        'kontrakAkanBerakhir',
        'totalSP'
    ));
    
}
}