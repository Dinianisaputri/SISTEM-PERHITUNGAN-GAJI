<?php

namespace App\Http\Controllers;

use App\Models\SalaryArchive;
use App\Models\Checklog;
use App\Models\SalaryDetail;
use Illuminate\Http\Request;
use App\Models\SalaryArchiveDetail;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\SalaryPeriod;
use App\Models\SalarySetting;
use App\Models\EmployeeWorkSchedule;
use App\Services\SalaryCalculationService;
use App\Models\DailyWorkSchedule;
use Carbon\carbon;
class SalaryArchiveController extends Controller
{
    public function index(Request $request)
{
    $query = SalaryArchive::query()
        ->with('checklogs.employee');

    // Filter tanggal mulai
    if ($request->filled('tanggal_mulai')) {
        $query->whereDate(
            'start_date',
            '>=',
            $request->tanggal_mulai
        );
    }

    // Filter tanggal selesai
    if ($request->filled('tanggal_selesai')) {
        $query->whereDate(
            'end_date',
            '<=',
            $request->tanggal_selesai
        );
    }
    // Filter nama karyawan
    if ($request->filled('nama_karyawan')) {

        $nama = $request->nama_karyawan;

        $query->whereHas('checklogs.employee', function ($q) use ($nama) {
            $q->where('name', 'like', '%' . $nama . '%');
        });
    }

    $archives = $query
        ->latest()
        ->get();

    return view(
        'salary_archives.index',
        compact('archives')
    );
}

public function show(Request $request, SalaryArchive $salaryArchive)
{
    // Arsip hasil perhitungan gaji dari halaman /gaji
    if ($salaryArchive->archive_type === 'salary') {
        $query = $salaryArchive->details();

        // Filter nama karyawan
        if ($request->filled('nama_karyawan')) {
            $nama = $request->nama_karyawan;

            $query->where('employee_name', 'like', '%' . $nama . '%');
        }

        $archiveDetails = $query
            ->orderBy('employee_name')
            ->get();

        return view(
            'salary_archives.show',
            compact('salaryArchive', 'archiveDetails')
        );
    }

    // Arsip checklog lama tetap menggunakan alur sebelumnya
    $query = $salaryArchive->checklogs()
        ->with('employee');

    if ($request->filled('filter_tanggal_mulai')) {
        $query->whereDate(
            'tanggal',
            '>=',
            $request->filter_tanggal_mulai
        );
    }

    if ($request->filled('filter_tanggal_selesai')) {
        $query->whereDate(
            'tanggal',
            '<=',
            $request->filter_tanggal_selesai
        );
    }

    if ($request->filled('nama_karyawan')) {
        $nama = $request->nama_karyawan;

        $query->whereHas('employee', function ($q) use ($nama) {
            $q->where('name', 'like', '%' . $nama . '%');
        });
    }

    $checklogs = $query
        ->orderBy('tanggal')
        ->orderBy('borongan_employee_id')
        ->get();

    return view(
        'salary_archives.show',
        compact('salaryArchive', 'checklogs')
    );
}
    // Hapus arsip beserta seluruh data checklog di dalamnya
    public function destroy(SalaryArchive $salaryArchive)
    {
        // Hapus semua data checklog yang ada di dalam arsip
        $salaryArchive->checklogs()->delete();

        // Hapus data arsip
        $salaryArchive->delete();

        return redirect()
            ->route('salary_archives.index')
            ->with(
                'success',
                'Arsip gaji dan seluruh data di dalamnya berhasil dihapus.'
            );
    }


    public function editChecklog(
        SalaryArchive $salaryArchive,
        Checklog $checklog
    ) {
        // Pastikan checklog memang milik arsip tersebut
        if ($checklog->salary_archive_id != $salaryArchive->id) {
            abort(404);
        }

        $checklog->load('employee');

        return view(
            'salary_archives.edit_checklog',
            compact('salaryArchive', 'checklog')
        );
    }


    public function updateChecklog(
        Request $request,
        SalaryArchive $salaryArchive,
        Checklog $checklog
    ) {
        // Pastikan checklog memang milik arsip tersebut
        if ($checklog->salary_archive_id != $salaryArchive->id) {
            abort(404);
        }

        $request->validate([
            'jam_istirahat' => [
                'required',
                'regex:/^\d+(\.[0-5][0-9])?$/'
            ],
        ], [
            'jam_istirahat.regex' =>
                'Format istirahat harus seperti 0.00, 0.30, 1.00, atau 1.30.'
        ]);


        $jamIstirahat = (string) $request->jam_istirahat;


        // Ubah format 1.30 menjadi 1 jam 30 menit
        if (str_contains($jamIstirahat, '.')) {

            [$jam, $menit] = explode(
                '.',
                $jamIstirahat
            );

            $jam = (int) $jam;

            $menit = (int) str_pad(
                $menit,
                2,
                '0'
            );

        } else {

            $jam = (int) $jamIstirahat;

            $menit = 0;
        }


        $breakMinutes = ($jam * 60) + $menit;


        $totalJamKerja = 0;

        $gaji = 0;


        // Hitung ulang berdasarkan scan masuk dan scan pulang
        if (
            $checklog->scan_masuk &&
            $checklog->scan_pulang
        ) {

            $masuk = \Carbon\Carbon::createFromFormat(
                'H:i:s',
                $checklog->scan_masuk
            );

            $pulang = \Carbon\Carbon::createFromFormat(
                'H:i:s',
                $checklog->scan_pulang
            );


            // Jika pulang melewati tengah malam
            if ($pulang->lessThanOrEqualTo($masuk)) {
                $pulang->addDay();
            }


            $totalMinutes = $masuk->diffInMinutes(
                $pulang
            );


            // Kurangi waktu istirahat
            $workMinutes = max(
                0,
                $totalMinutes - $breakMinutes
            );


            $totalJamKerja = $workMinutes / 60;


            // Rp18.000 per jam
            $gaji = $workMinutes * (18000 / 60);
        }


        // Simpan perubahan
        $checklog->update([
            'jam_istirahat' => $jamIstirahat,
            'total_jam_kerja' => $totalJamKerja,
            'hourly_rate' => 18000,
            'gaji' => $gaji,
        ]);


        // Hitung ulang total arsip
        $salaryArchive->update([
            'total_salary' => $salaryArchive
                ->checklogs()
                ->sum('gaji'),

            'total_checklogs' => $salaryArchive
                ->checklogs()
                ->count(),

            'total_employees' => $salaryArchive
                ->checklogs()
                ->distinct('borongan_employee_id')
                ->count('borongan_employee_id'),
        ]);


        return redirect()
            ->route(
                'salary_archives.show',
                $salaryArchive
            )
            ->with(
                'success',
                'Data checklog berhasil diperbarui.'
            );
    }

    // Hapus satu checklog dari arsip
    public function destroyChecklog(
        SalaryArchive $salaryArchive,
        Checklog $checklog
    ) {
        // Pastikan checklog memang milik arsip tersebut
        if ($checklog->salary_archive_id != $salaryArchive->id) {
            abort(404);
        }


        // Hapus checklog
        $checklog->delete();


        // Hitung ulang total arsip
        $salaryArchive->update([
            'total_salary' => $salaryArchive
                ->checklogs()
                ->sum('gaji'),

            'total_checklogs' => $salaryArchive
                ->checklogs()
                ->count(),

            'total_employees' => $salaryArchive
                ->checklogs()
                ->distinct('borongan_employee_id')
                ->count('borongan_employee_id'),
        ]);


        return redirect()
            ->route(
                'salary_archives.show',
                $salaryArchive
            )
            ->with(
                'success',
                'Data checklog berhasil dihapus.'
            );
    }

public function editSalaryDetail(
    SalaryArchive $salaryArchive,
    SalaryArchiveDetail $detail
) {
    // Fitur ini hanya untuk arsip hasil perhitungan gaji.
    abort_unless($salaryArchive->archive_type === 'salary', 404);

    // Pastikan detail benar-benar milik arsip yang dibuka.
    abort_unless(
        (int) $detail->salary_archive_id === (int) $salaryArchive->id,
        404
    );

    return view(
        'salary_archives.edit_salary_detail',
        compact('salaryArchive', 'detail')
    );
}


public function updateSalaryDetail(
    Request $request,
    SalaryArchive $salaryArchive,
    SalaryArchiveDetail $detail
) {
    abort_unless($salaryArchive->archive_type === 'salary', 404);

    abort_unless(
        (int) $detail->salary_archive_id === (int) $salaryArchive->id,
        404
    );

    $validated = $request->validate([
        'potongan' => ['nullable', 'array'],
        'potongan.*.nama_potongan' => [
            'required',
            'string',
            'max:255',
        ],
        'potongan.*.nominal' => [
            'required',
            'numeric',
            'min:0',
            'max:9999999999999.99',
        ],
        'potongan.*.keterangan' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ], [
        'potongan.*.nama_potongan.required' =>
            'Nama potongan wajib diisi.',
        'potongan.*.nominal.required' =>
            'Nominal potongan wajib diisi.',
        'potongan.*.nominal.numeric' =>
            'Nominal potongan harus berupa angka.',
        'potongan.*.nominal.min' =>
            'Nominal potongan tidak boleh negatif.',
    ]);

    $items = collect($validated['potongan'] ?? [])
        ->map(function ($item) {
            return [
                'nama_potongan' => trim($item['nama_potongan']),
                'nominal' => round((float) $item['nominal'], 2),
                'keterangan' => trim($item['keterangan'] ?? ''),
            ];
        })
        ->values()
        ->all();

    $totalPotonganTambahan = collect($items)->sum('nominal');

    DB::transaction(function () use (
        $detail,
        $items,
        $totalPotonganTambahan
    ) {
        $potonganAwal = (float) $detail->total_potongan;

        $gajiBersih = (float) $detail->gaji_kotor
            - $potonganAwal
            + (float) ($detail->total_tambahan ?? 0)
            - $totalPotonganTambahan;

        $detail->update([
            'rincian_potongan_tambahan' => $items,
            'potongan_tambahan' => $totalPotonganTambahan,
            'alasan_potongan_tambahan' => collect($items)
                ->pluck('keterangan')
                ->filter()
                ->implode("\n"),
            'gaji_bersih' => $gajiBersih,
        ]);
    });

    return redirect()
        ->route('salary_archives.details.edit', [
            'salaryArchive' => $salaryArchive->id,
            'detail' => $detail->id,
        ])
        ->with('success', 'Rincian potongan arsip berhasil disimpan.');
}

public function slipPdf(SalaryArchive $salaryArchive)
{
    abort_unless($salaryArchive->archive_type === 'salary', 404);

    
$details = $salaryArchive->details()
    ->with('employee')
    ->orderBy('employee_name')
    ->get();

abort_if(
    $details->isEmpty(),
    404,
    'Data slip gaji tidak ditemukan.'
);

// Ambil detail gaji aktif untuk periode arsip.
$employeeIds = $details
    ->filter(fn ($detail) => $detail->employee_id)
    ->pluck('employee_id')
    ->unique()
    ->values();

$salaryDetails = SalaryDetail::query()
    ->with('deductions')
    ->whereIn('employee_id', $employeeIds)
    ->whereHas('period', function ($query) use ($salaryArchive) {
        $query->whereDate(
            'tanggal_mulai',
            $salaryArchive->start_date
        )->whereDate(
            'tanggal_selesai',
            $salaryArchive->end_date
        );
    })
    ->latest('id')
    ->get()
    ->unique('employee_id')
    ->keyBy('employee_id');

// Lengkapi rincian yang ditampilkan tanpa mengubah arsip di database.
foreach ($details as $detail) {
    $rincianArsip = $detail->rincian_potongan ?? [];

    if (is_string($rincianArsip)) {
        $rincianArsip = json_decode($rincianArsip, true) ?? [];
    }

    if (!is_array($rincianArsip)) {
        $rincianArsip = [];
    }

    $salaryDetail = $salaryDetails->get($detail->employee_id);

    if ($salaryDetail) {
        $rincianAktif = $salaryDetail->deductions
            ->map(function ($potongan) {
                return [
                    'nama_potongan' => $potongan->nama_potongan,
                    'nominal' => (float) $potongan->nominal,
                    'keterangan' => $potongan->keterangan,
                ];
            })
            ->values()
            ->all();

        foreach ($rincianAktif as $potonganAktif) {
            $nama = $potonganAktif['nama_potongan'] ?? '';

            $sudahAda = collect($rincianArsip)->contains(
                function ($potonganArsip) use ($nama, $potonganAktif) {
                    return ($potonganArsip['nama_potongan'] ?? '') === $nama
                        && ($potonganArsip['keterangan'] ?? '') ===
                            ($potonganAktif['keterangan'] ?? '');
                }
            );

            if (!$sudahAda) {
                $rincianArsip[] = $potonganAktif;
            }
        }
    }

    $detail->setAttribute('rincian_potongan', $rincianArsip);
}

$pdf = Pdf::loadView('salary_archives.slip_pdf', [

        'salaryArchive' => $salaryArchive,
        'details' => $details,
    ])->setPaper('a4', 'portrait');

    return $pdf->stream(
        'Slip-Gaji-' . $salaryArchive->id . '.pdf'
    );
}

public function exportFinance(
    SalaryArchive $salaryArchive,
    SalaryCalculationService $salaryService
    ) {

    abort_unless($salaryArchive->archive_type === 'salary', 404);

    $filters = $salaryArchive->filters ?? [];
    $bagianArsip = trim((string) ($filters['bagian'] ?? ''));

    // Sumber utama tetap detail arsip yang dipilih.
    $query = $salaryArchive->details();

    // Pengaman tambahan agar bagian mengikuti filter arsip.
    if ($bagianArsip !== '') {
        $query->whereRaw(
            'UPPER(TRIM(bagian)) = ?',
            [strtoupper($bagianArsip)]
        );
    }

    $details = $query
    ->orderBy('employee_name')
    ->get();

    abort_if(
        $details->isEmpty(),
        404,
        'Tidak ada detail gaji untuk arsip ini.'
    );

    $start = \Carbon\Carbon::parse($salaryArchive->start_date);
    $end = \Carbon\Carbon::parse($salaryArchive->end_date);

    abort_if(
        $end->lt($start),
        422,
        'Periode arsip tidak valid.'
    );

    // Tanggal dibuat dinamis berdasarkan periode arsip.
    $dates = [];
    $cursor = $start->copy();

    while ($cursor->lte($end)) {
        $dates[] = $cursor->copy();
        $cursor->addDay();
    }

    $employeeIds = $details->pluck('employee_id')
        ->filter()
        ->unique()
        ->values();

    // Ambil nomor rekening hanya untuk karyawan pada arsip ini.
    $employees = DB::table('employees')
        ->whereIn('id', $employeeIds)
        ->get(['id', 'no_rekening'])
        ->keyBy('id');

    // Data kehadiran harian. Tidak menghitung ulang nilai gaji.
    // Data fingerprint untuk rekonstruksi rincian harian arsip lama.
// Ambil sampai dini hari berikutnya agar scan pulang shift malam
// tetap dapat dipasangkan dengan tanggal kerja sebelumnya.
$logs = AttendanceLog::query()
    ->whereIn(
        'pin',
        $details->pluck('fingerprint_pin')
            ->filter()
            ->map(fn ($pin) => trim((string) $pin))
            ->unique()
            ->values()
    )
    ->whereBetween('punched_at', [
        $start->copy()->startOfDay(),
        $end->copy()->addDay()->startOfDay()->addHours(4),
    ])
    ->orderBy('punched_at')
    ->get(['pin', 'punched_at'])
    ->groupBy(fn ($log) => trim((string) $log->pin));

$holidayDates = Holiday::query()
    ->whereBetween('tanggal', [
        $start->toDateString(),
        $end->toDateString(),
    ])
    ->pluck('tanggal')
    ->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'))
    ->flip();

$period = SalaryPeriod::query()
    ->whereDate('tanggal_mulai', $start->toDateString())
    ->whereDate('tanggal_selesai', $end->toDateString())
    ->first();

$salaryDetails = $period
    ? SalaryDetail::query()
        ->where('salary_period_id', $period->id)
        ->get()
        ->keyBy('employee_id')
    : collect();
    // Buat workbook baru, tidak menggunakan template lama.
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Laporan Gaji');

    $dailyStartCol = 5; // E
    $dailyColumnCount = count($dates) * 4;
    $summaryStartCol = $dailyStartCol + $dailyColumnCount;

    $summaryHeaders = [
        'Hari Hadir',
        'Tarif Harian',
        'Gaji Pokok',
        'Jam Lembur Biasa',
        'Jam Lembur Libur',
        'Total Lembur',
        'Uang Makan',
        'Potongan Awal',
        'Nominal Tambahan',
        'Potongan Tambahan',
        'Gaji Kotor',
        'Gaji Bersih',
        'No. Rekening',
        'Tanda Tangan',
    ];

    $lastCol = $summaryStartCol + count($summaryHeaders) - 1;
    $lastLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastCol);

    // Header perusahaan dan periode.
    $sheet->mergeCells("A1:{$lastLetter}1");
    $sheet->setCellValue('A1', 'PT. STARFOOD INTERNATIONAL');

    $sheet->mergeCells("A2:{$lastLetter}2");
    $sheet->setCellValue('A2', 'LAPORAN PERHITUNGAN GAJI KARYAWAN');

    $sheet->mergeCells("A3:{$lastLetter}3");
    $sheet->setCellValue(
        'A3',
        'Periode: ' . $start->format('d/m/Y')
            . ' - ' . $end->format('d/m/Y')
    );

    $sheet->mergeCells("A4:{$lastLetter}4");
    $sheet->setCellValue(
        'A4',
        'Bagian: ' . ($bagianArsip !== '' ? $bagianArsip : 'Semua Bagian')
    );

    // Kolom identitas karyawan.
    $baseHeaders = [
        1 => 'No.',
        2 => 'PIN',
        3 => 'Nama Karyawan',
        4 => 'Bagian',
    ];

    foreach ($baseHeaders as $col => $label) {
        $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        $sheet->mergeCells("{$letter}5:{$letter}6");
        $sheet->setCellValue("{$letter}5", $label);
    }

    // Kelompok kolom harian: HARI, L, LL, UM.
    $dailySubHeaders = ['HARI', 'L', 'LL', 'UM'];

    foreach ($dates as $index => $date) {
        $firstCol = $dailyStartCol + ($index * 4);
        $lastDailyCol = $firstCol + 3;

        $firstLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($firstCol);
        $lastDailyLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastDailyCol);

        $sheet->mergeCells("{$firstLetter}5:{$lastDailyLetter}5");
        $sheet->setCellValue(
            "{$firstLetter}5",
            $date->format('d/m')
        );

        foreach ($dailySubHeaders as $offset => $label) {
            $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($firstCol + $offset);
            $sheet->setCellValue("{$letter}6", $label);
                }
            }

            foreach ($summaryHeaders as $index => $label) {
                $col = $summaryStartCol + $index;
                $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);

                $sheet->mergeCells("{$letter}5:{$letter}6");
                $sheet->setCellValue("{$letter}5", $label);
            }

            $firstDataRow = 7;

            foreach ($details as $index => $detail) {
                $row = $firstDataRow + $index;

                $sheet->setCellValue("A{$row}", $index + 1);
                $sheet->setCellValue("B{$row}", $detail->fingerprint_pin);
                $sheet->setCellValue("C{$row}", $detail->employee_name);
                $sheet->setCellValue("D{$row}", $detail->bagian);

            $daily = $this->financeDailyBreakdown(
            $detail,
            $start,
            $end,
            $logs->get(trim((string) $detail->fingerprint_pin), collect()),
            $holidayDates,
            $salaryDetails->get($detail->employee_id),
            $salaryService
        );

        foreach ($dates as $dayIndex => $date) {
            $firstCol = $dailyStartCol + ($dayIndex * 4);
            $key = $date->format('Y-m-d');

            $day = $daily[$key] ?? [
                'hari' => 0,
                'jam_lembur_biasa' => 0,
                'jam_lembur_libur' => 0,
                'uang_makan_lembur' => 0,
            ];

            $valuesDaily = [
                $day['hari'],
                $day['jam_lembur_biasa'],
                $day['jam_lembur_libur'],
                $day['uang_makan_lembur'],
            ];

            foreach ($valuesDaily as $offset => $value) {
                $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate
                    ::stringFromColumnIndex($firstCol + $offset);

                $sheet->setCellValue(
                    "{$letter}{$row}",
                    $value
                );
            }
        }
        $account = $employees->get($detail->employee_id);

        $values = [
            (float) $detail->hari_hadir,
            (float) $detail->tarif_harian,
            (float) $detail->gaji_pokok,
            (float) $detail->jam_lembur_biasa,
            (float) $detail->jam_lembur_libur,
            (float) $detail->total_lembur,
            (float) $detail->uang_makan_lembur,
            (float) $detail->total_potongan,
            (float) ($detail->total_tambahan ?? 0),
            (float) ($detail->potongan_tambahan ?? 0),
            (float) $detail->gaji_kotor,
            (float) $detail->gaji_bersih,
            $account->no_rekening ?? '',
            '',
        ];

        foreach ($values as $valueIndex => $value) {
            $col = $summaryStartCol + $valueIndex;
            $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $sheet->setCellValue("{$letter}{$row}", $value);
        }
    }
    $lastDataRow = $firstDataRow + $details->count() - 1;
    $totalRow = $lastDataRow + 1;

    // Baris total berdasarkan nilai detail arsip.
    $sheet->mergeCells("A{$totalRow}:D{$totalRow}");
    $sheet->setCellValue("A{$totalRow}", 'TOTAL');

    // Jumlah kehadiran harian.
    foreach ($dates as $dayIndex => $date) {
        $col = $dailyStartCol + ($dayIndex * 4);
        $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        $sheet->setCellValue(
            "{$letter}{$totalRow}",
            "=SUM({$letter}{$firstDataRow}:{$letter}{$lastDataRow})"
        );
    }

   
    // Kolom nominal/jam dijumlahkan; kolom rekening dan tanda tangan tidak.
    $sumIndexes = [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];

    foreach ($sumIndexes as $valueIndex) {
        $col = $summaryStartCol + $valueIndex;
        $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);

        $sheet->setCellValue(
            "{$letter}{$totalRow}",
            "=SUM({$letter}{$firstDataRow}:{$letter}{$lastDataRow})"
        );
    }

   
    
    // BAGIAN TANDA TANGAN
    $signatureDateRow = $totalRow + 1;
    $signatureTitleRow = $totalRow + 2;
    $signatureNameRow = $totalRow + 6;

    // Batas area kiri dan kanan mengikuti lebar laporan.
    $leftEndCol = (int) floor($lastCol / 2);
    $rightStartCol = $leftEndCol + 1;

    $leftEndLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate
        ::stringFromColumnIndex($leftEndCol);

    $rightStartLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate
        ::stringFromColumnIndex($rightStartCol);

    // Tanggal ekspor di area kiri.
    $sheet->mergeCells("A{$signatureDateRow}:D{$signatureDateRow}");
    $sheet->setCellValue(
        "A{$signatureDateRow}",
        'Lamongan, ' . \Carbon\Carbon::now()
            ->locale('id')
            ->translatedFormat('d F Y')
    );

    // Menyetujui tepat di tengah laporan.
    $sheet->mergeCells(
        "A{$signatureTitleRow}:{$lastLetter}{$signatureTitleRow}"
    );
    $sheet->setCellValue("A{$signatureTitleRow}", 'Menyetujui');

    // Nama di sisi kiri dan kanan.
    $sheet->mergeCells(
        "A{$signatureNameRow}:{$leftEndLetter}{$signatureNameRow}"
    );
    $sheet->setCellValue(
        "A{$signatureNameRow}",
        'Asmaul Chusnah Hidayah'
    );

    $sheet->mergeCells(
        "{$rightStartLetter}{$signatureNameRow}:{$lastLetter}{$signatureNameRow}"
    );
    $sheet->setCellValue(
        "{$rightStartLetter}{$signatureNameRow}",
        'Ahmad Haji Ikhrom'
    );

    // Perataan tanggal dan tulisan.
    $sheet->getStyle("A{$signatureDateRow}:D{$signatureDateRow}")
        ->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
        );

    $sheet->getStyle(
        "A{$signatureTitleRow}:{$lastLetter}{$signatureTitleRow}"
    )->getAlignment()->setHorizontal(
        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
    );

    $sheet->getStyle(
        "A{$signatureNameRow}:{$lastLetter}{$signatureNameRow}"
    )->getAlignment()->setHorizontal(
        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
    );

    $sheet->getStyle(
        "A{$signatureNameRow}:{$lastLetter}{$signatureNameRow}"
    )->getFont()->setBold(true);

    // Ruang tanda tangan manual.
    for ($r = $signatureTitleRow + 1; $r < $signatureNameRow; $r++) {
        $sheet->getRowDimension($r)->setRowHeight(15);
    }

    // Menyetujui di tengah area tanda tangan.
    $sheet->mergeCells(
        "A{$signatureTitleRow}:{$lastLetter}{$signatureTitleRow}"
    );
    $sheet->setCellValue("A{$signatureTitleRow}", 'Menyetujui');

    $sheet->getStyle(
        "A{$signatureTitleRow}:{$lastLetter}{$signatureTitleRow}"
    )->getAlignment()->setHorizontal(
        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
    );

    // Beri ruang tanda tangan yang cukup.
    for ($r = $signatureTitleRow + 1; $r < $signatureNameRow; $r++) {
        $sheet->getRowDimension($r)->setRowHeight(18);
    }

    // Nama penandatangan di kiri dan kanan.
    $sheet->mergeCells(
        "A{$signatureNameRow}:{$leftEndLetter}{$signatureNameRow}"
    );
    $sheet->setCellValue(
        "A{$signatureNameRow}",
        'Asmaul Chusnah Hidayah'
    );

    $sheet->mergeCells(
        "{$rightStartLetter}{$signatureNameRow}:{$lastLetter}{$signatureNameRow}"
    );
    $sheet->setCellValue(
        "{$rightStartLetter}{$signatureNameRow}",
        'Ahmad Haji Ikhrom'
    );

    $sheet->getStyle(
        "A{$signatureNameRow}:{$lastLetter}{$signatureNameRow}"
    )->getAlignment()->setHorizontal(
        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
    );

    $sheet->getStyle(
        "A{$signatureNameRow}:{$lastLetter}{$signatureNameRow}"
    )->getFont()->setBold(true);

    $sheet->getRowDimension($signatureTitleRow)->setRowHeight(22);
    $sheet->getRowDimension($signatureNameRow)->setRowHeight(22);


    // Gaya laporan: header, garis tabel, dan format angka.
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => '244062'],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            'wrapText' => true,
        ],
    ];

    $sheet->getStyle("A5:{$lastLetter}6")->applyFromArray($headerStyle);

    $sheet->getStyle("A1:{$lastLetter}1")->applyFromArray([
        'font' => ['bold' => true, 'size' => 16],
        'alignment' => ['horizontal' => 'center'],
    ]);

    $sheet->getStyle("A2:{$lastLetter}2")->applyFromArray([
        'font' => ['bold' => true, 'size' => 13],
        'alignment' => ['horizontal' => 'center'],
    ]);

    $sheet->getStyle("A3:{$lastLetter}4")->applyFromArray([
        'alignment' => ['horizontal' => 'center'],
    ]);

    $sheet->getStyle("A5:{$lastLetter}{$totalRow}")
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(
            \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
        );

    $sheet->getStyle("A{$totalRow}:{$lastLetter}{$totalRow}")
        ->getFont()->setBold(true);

    $sheet->getStyle("A7:{$lastLetter}{$lastDataRow}")
        ->getAlignment()->setVertical(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        );

    // Format nominal uang.
    $moneyIndexes = [1, 2, 5, 6, 7, 8, 9, 10, 11];

    foreach ($moneyIndexes as $valueIndex) {
        $col = $summaryStartCol + $valueIndex;
        $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);

        $sheet->getStyle("{$letter}{$firstDataRow}:{$letter}{$totalRow}")
            ->getNumberFormat()
            ->setFormatCode('"Rp" #,##0;[Red]("Rp" #,##0);-');
    }

    $sheet->getColumnDimension('A')->setWidth(6);
    $sheet->getColumnDimension('B')->setWidth(10);
    $sheet->getColumnDimension('C')->setWidth(30);
    $sheet->getColumnDimension('D')->setWidth(16);

    for ($col = $dailyStartCol; $col < $summaryStartCol; $col++) {
        $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        $sheet->getColumnDimension($letter)->setWidth(9);
    }

    foreach (range($summaryStartCol, $lastCol) as $col) {
        $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        $sheet->getColumnDimension($letter)->setWidth(16);
    }

    $sheet->getColumnDimension(
        \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($summaryStartCol + 12)
    )->setWidth(22);

    $sheet->getColumnDimension($lastLetter)->setWidth(18);
    $sheet->getRowDimension(5)->setRowHeight(30);
    $sheet->getRowDimension(6)->setRowHeight(32);
    $sheet->freezePane('E7');
    $sheet->setAutoFilter("A6:{$lastLetter}{$lastDataRow}");

    $sheet->getPageSetup()
        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
        ->setFitToWidth(1)
        ->setFitToHeight(0);

    $sheet->getPageSetup()->setFitToPage(true);

    // Pengaturan area cetak laporan beserta tanda tangan
    $printLastRow = $signatureNameRow + 1;

    $sheet->getPageSetup()
        ->setPrintArea("A1:{$lastLetter}{$printLastRow}")
        ->setOrientation(
            \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
        )
        ->setPaperSize(
            \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4
        )
        ->setFitToWidth(1)
        ->setFitToHeight(0);

    $sheet->getPageSetup()->setFitToPage(true);

    // Pusatkan laporan secara horizontal saat dicetak.
    $sheet->getPageMargins()
        ->setLeft(0.25)
        ->setRight(0.25)
        ->setTop(0.40)
        ->setBottom(0.40);

    $sheet->getPageSetup()
        ->setHorizontalCentered(true);

    // Ulangi header tabel jika laporan tercetak ke beberapa halaman.
    $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 6);

    $filename = 'Finance-Gaji-' . $salaryArchive->id . '.xlsx';
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

    return response()->streamDownload(
        function () use ($writer) {
            $writer->save('php://output');
        },
        $filename,
        [
            'Content-Type' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]
    );
}
    private function financeDailyBreakdown(
    SalaryArchiveDetail $detail,
    Carbon $start,
    Carbon $end,
    $logs,
    $holidayDates,
    $salaryDetail,
    SalaryCalculationService $salaryService
): array {
    $result = [];

    // Utamakan snapshot harian yang tersimpan di arsip.
    $snapshot = $detail->daily_breakdown;

    if (is_array($snapshot) && count($snapshot) > 0) {
        foreach ($snapshot as $day) {
            if (empty($day['tanggal'])) {
                continue;
            }

            $tanggal = Carbon::parse($day['tanggal'])->format('Y-m-d');

            if (
                $tanggal < $start->toDateString()
                || $tanggal > $end->toDateString()
            ) {
                continue;
            }

            $result[$tanggal] = [
                'hari' => (
                    (float) ($day['jam_kerja'] ?? 0) > 0
                    || (float) ($day['gaji_pokok'] ?? 0) > 0
                ) ? 1 : 0,

                'jam_lembur_biasa' =>
                    (float) ($day['jam_lembur_biasa'] ?? 0),

                'jam_lembur_libur' =>
                    (float) ($day['jam_lembur_libur'] ?? 0),

                'uang_makan_lembur' =>
                    (float) ($day['uang_makan_lembur'] ?? 0),
            ];
        }

        return $result;
    }

    // Arsip lama tanpa snapshot: rekonstruksi dari fingerprint.
    $employee = Employee::find($detail->employee_id);

    if (!$employee || !$detail->fingerprint_pin) {
        return $result;
    }

    $jenis = strtolower(trim((string) $detail->jenis_karyawan));
    $isBorongan = $jenis === 'borongan';
    $isValidType = in_array($jenis, ['borongan', 'harian', 'bulanan'], true);

    $isDapur = strtoupper(trim((string) $detail->bagian)) === 'DAPUR';

    $tarifHarian = match ($jenis) {
        'harian' => SalarySetting::value('harian_per_hari', 127855),
        'bulanan' => (float) ($salaryDetail?->tarif_harian ?? $detail->tarif_harian ?? 0),
        default => 0,
    };

    $tarifJam = $isBorongan
        ? SalarySetting::value('borongan_per_jam', 18000)
        : 0;

    $jamIstirahat = (float) ($salaryDetail?->jam_istirahat ?? 1);

    $grouped = $this->financeGroupAttendanceByShiftDate($logs);

    foreach ($grouped as $tanggal => $dayLogs) {
        if (
            $tanggal < $start->toDateString()
            || $tanggal > $end->toDateString()
        ) {
            continue;
        }

        $dayLogs = $dayLogs->sortBy('punched_at')->values();
        $jumlahScan = $dayLogs->count();

        $result[$tanggal] = [
            'hari' => 0,
            'jam_lembur_biasa' => 0,
            'jam_lembur_libur' => 0,
            'uang_makan_lembur' => 0,
        ];

        // Minimal dua scan agar jam kerja dapat dihitung.
        if ($jumlahScan < 2 || !$isValidType) {
            continue;
        }

        $masuk = Carbon::parse($dayLogs->first()->punched_at);
        $pulang = Carbon::parse($dayLogs->last()->punched_at);

        if ($pulang->lessThanOrEqualTo($masuk)) {
            $pulang->addDay();
        }

        $jamBruto = $masuk->diffInMinutes($pulang) / 60;
        $jamKerja = max($jamBruto - $jamIstirahat, 0);

        if ($jamKerja <= 0) {
            continue;
        }

        $result[$tanggal]['hari'] = 1;

        if ($isBorongan) {
            $hasil = $salaryService->calculateBorongan(
                $masuk->format('H:i:s'),
                $pulang->format('H:i:s'),
                $jamIstirahat,
                $tarifJam
            );

            $result[$tanggal]['uang_makan_lembur'] =
                (float) ($hasil['uang_makan_lembur'] ?? 0);

            continue;
        }

        // Jadwal khusus karyawan lebih diutamakan daripada jadwal penempatan.
        $schedule = $this->financeGetWorkSchedule(
            $employee,
            $tanggal
        );

        $isHoliday = $holidayDates->has($tanggal);

        $hasil = $salaryService->calculateHarianBulanan(
            (float) $tarifHarian,
            $jamKerja,
            $isHoliday,
            $isDapur,
            $schedule,
            $tanggal,
            $pulang->format('H:i:s')
        );

        $result[$tanggal]['jam_lembur_biasa'] =
            (float) ($hasil['jam_lembur_biasa'] ?? 0);

        $result[$tanggal]['jam_lembur_libur'] =
            (float) ($hasil['jam_lembur_libur'] ?? 0);

        $result[$tanggal]['uang_makan_lembur'] =
            (float) ($hasil['uang_makan_lembur'] ?? 0);
    }

    return $result;
}

private function financeGroupAttendanceByShiftDate($logs)
{
    $daily = $logs
        ->sortBy('punched_at')
        ->groupBy(fn ($log) => Carbon::parse($log->punched_at)->format('Y-m-d'))
        ->map(fn ($dayLogs) => $dayLogs->sortBy('punched_at')->values());

    $dates = $daily->keys()->sort()->values();

    foreach ($dates as $date) {
        $current = $daily->get($date);

        if (!$current || $current->count() < 3) {
            continue;
        }

        $first = Carbon::parse($current->first()->punched_at);

        if ($first->hour >= 4) {
            continue;
        }

        $previousDate = $first->copy()->subDay()->format('Y-m-d');
        $previous = $daily->get($previousDate);

        if (!$previous || $previous->count() !== 1) {
            continue;
        }

        $previousTime = Carbon::parse($previous->first()->punched_at);
        $elapsedMinutes = $previousTime->diffInMinutes($first);

        if ($elapsedMinutes <= 0 || $elapsedMinutes > 22 * 60) {
            continue;
        }

        $daily->put(
            $previousDate,
            $previous->push($current->first())->sortBy('punched_at')->values()
        );

        $daily->put($date, $current->slice(1)->values());
    }

    return $daily->sortKeys();
}

private function financeGetWorkSchedule(
    Employee $employee,
    string $tanggal
): ?object {
    $jadwalKhusus = EmployeeWorkSchedule::with('workSchedule')
        ->where('employee_id', $employee->id)
        ->whereDate('tanggal', $tanggal)
        ->first();

    if ($jadwalKhusus?->workSchedule) {
        return $jadwalKhusus->workSchedule;
    }

    if (empty($employee->penempatan)) {
        return null;
    }

    return DailyWorkSchedule::with('workSchedule')
        ->whereDate('tanggal', $tanggal)
        ->where('penempatan', $employee->penempatan)
        ->first()?->workSchedule;
}
}