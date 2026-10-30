<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\SalaryDetail;
use App\Models\SalaryPeriod;
use App\Models\SalaryDeduction;
use App\Services\SalaryCalculationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\EmployeeWorkSchedule;
use App\Models\DailyWorkSchedule;
use App\Models\SalarySetting;
use Illuminate\Support\Facades\DB;
use App\Models\SalaryArchive;
use App\Models\SalaryArchiveDetail;

class SalaryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN UTAMA GAJI
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        SalaryCalculationService $salaryService
    ) {
        /*
        |--------------------------------------------------------------------------
        | Periode
        |--------------------------------------------------------------------------
        */

        $tanggalMulai = $request->input(
            'tanggal_mulai',
            now()->startOfMonth()->format('Y-m-d')
        );

        $tanggalSelesai = $request->input(
            'tanggal_selesai',
            now()->endOfMonth()->format('Y-m-d')
        );

        /*
        |--------------------------------------------------------------------------
        | Validasi periode
        |--------------------------------------------------------------------------
        */

        try {
            $startDate = Carbon::parse($tanggalMulai)->startOfDay();
            $endDate = Carbon::parse($tanggalSelesai)->endOfDay();
        } catch (\Exception $e) {
            $startDate = now()->startOfMonth()->startOfDay();
            $endDate = now()->endOfMonth()->endOfDay();

            $tanggalMulai = $startDate->format('Y-m-d');
            $tanggalSelesai = $endDate->format('Y-m-d');
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan tanggal mulai tidak lebih besar
        |--------------------------------------------------------------------------
        */

        if ($startDate->greaterThan($endDate)) {
            [$startDate, $endDate] = [
                $endDate,
                $startDate,
            ];

            $tanggalMulai = $startDate->format('Y-m-d');
            $tanggalSelesai = $endDate->format('Y-m-d');
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Karyawan
        |--------------------------------------------------------------------------
        */

        $query = Employee::query()
            ->where('is_active', true)
            ->orderBy('name');

        if ($request->filled('jenis_karyawan')) {
            $query->where(
                'jenis_karyawan',
                $request->jenis_karyawan
            );
        }

        if ($request->filled('bagian')) {
            $query->where(
                'bagian',
                $request->bagian
            );
        }

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )->orWhere(
                    'fingerprint_pin',
                    'like',
                    "%{$search}%"
                )->orWhere(
                    'penempatan',
                    'like',
                    "%{$search}%"
                );
            });
        }

        $employees = $query->get();

        /*
        |--------------------------------------------------------------------------
        | PIN Karyawan
        |--------------------------------------------------------------------------
        */

        $pins = $employees
            ->pluck('fingerprint_pin')
            ->filter()
            ->map(fn ($pin) => (string) $pin)
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Absensi Fingerprint
        |--------------------------------------------------------------------------
        */

        $attendanceLogs = AttendanceLog::query()
            ->whereIn('pin', $pins)
            ->whereBetween('punched_at', [
                $startDate,
                $endDate,
            ])
            ->orderBy('punched_at')
            ->get()
            ->groupBy('pin');

        /*
        |--------------------------------------------------------------------------
        | Hari Libur
        |--------------------------------------------------------------------------
        |
        | Hari libur hanya berasal dari tabel holidays.
        | Sabtu/Minggu TIDAK otomatis dianggap libur.
        |
        */

        $holidayDates = Holiday::query()
            ->whereBetween('tanggal', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->pluck('tanggal')
            ->map(function ($tanggal) {
                return Carbon::parse($tanggal)->format('Y-m-d');
            })
            ->flip();

        /*
        |--------------------------------------------------------------------------
        | Periode Gaji
        |--------------------------------------------------------------------------
        */

        $period = SalaryPeriod::query()
            ->whereDate(
                'tanggal_mulai',
                $startDate->toDateString()
            )
            ->whereDate(
                'tanggal_selesai',
                $endDate->toDateString()
            )
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Detail Gaji
        |--------------------------------------------------------------------------
        */

        $salaryDetails = collect();

        if ($period) {
            $salaryDetails = SalaryDetail::query()
                ->where(
                    'salary_period_id',
                    $period->id
                )
                ->get()
                ->keyBy('employee_id');
        }

        /*
        |--------------------------------------------------------------------------
        | Perhitungan Semua Karyawan
        |--------------------------------------------------------------------------
        */
        $salaryEmployees = $employees->map(
            function ($employee) use (
                $attendanceLogs,
                $salaryDetails,
                $salaryService,
                $holidayDates
            ) {
                $logs = $attendanceLogs->get(
                    (string) $employee->fingerprint_pin,
                    collect()
                );

                $salaryDetail = $salaryDetails->get(
                    $employee->id
                );

                /*
                |--------------------------------------------------------------------------
                | Jenis Karyawan
                |--------------------------------------------------------------------------
                */

                $jenisKaryawan = strtolower(
                    trim(
                        (string) ($employee->jenis_karyawan ?? '')
                    )
                );

                $isBorongan = $jenisKaryawan === 'borongan';
                $isHarian = $jenisKaryawan === 'harian';
                $isBulanan = $jenisKaryawan === 'bulanan';

                $isDapur = strtoupper(
                    trim(
                        (string) ($employee->bagian ?? '')
                    )
                ) === 'DAPUR';

                $isValidType =
                    $isBorongan ||
                    $isHarian ||
                    $isBulanan;

                /*
                |--------------------------------------------------------------------------
                | Tarif
                |--------------------------------------------------------------------------
                */

                if ($isHarian) {
                    $tarifHarian = $this->salarySetting('harian_per_hari', 127855);
                } elseif ($isBulanan) {
                    $tarifHarian = $salaryDetail
                        ? (float) $salaryDetail->tarif_harian
                        : 0;
                } else {
                    $tarifHarian = 0;
                }

                $tarifJam = $isBorongan
                    ? $this->salarySetting('borongan_per_jam', 18000)
                    : 0;

                $jamIstirahat = $salaryDetail
                    ? (float) $salaryDetail->jam_istirahat
                    : 1;

                /*
                |--------------------------------------------------------------------------
                | Kelompokkan Absensi Berdasarkan Tanggal
                |--------------------------------------------------------------------------
                */

                $dailyAttendance = $this->groupAttendanceByShiftDate($logs)
                    ->map(
                        function ($dayLogs, $tanggalShift) use (
                            $salaryService,
                            $tarifHarian,
                            $tarifJam,
                            $jamIstirahat,
                            $holidayDates,
                            $isBorongan,
                            $isDapur,
                            $isValidType,
                            $employee
                        ) {
                            $dayLogs = $dayLogs
                                ->sortBy('punched_at')
                                ->values();

                            $first = $dayLogs->first();
                            $last = $dayLogs->last();

                            if (!$first) {
                                return null;
                            }

                            $jamMasuk = Carbon::parse(
                                $first->punched_at
                            );

                            $tanggal = $tanggalShift;

                   
                    // Jadwal khusus karyawan diprioritaskan, lalu jadwal harian berdasarkan penempatan.
                    $schedule = $this->getWorkSchedule(
                        $employee->id,
                        $tanggal
                    );

                    $jamMasukJadwal = $schedule?->jam_masuk;
                    $jamPulangJadwal = $schedule?->jam_pulang;

                            /*
                            |--------------------------------------------------------------------------
                            | Cek Hari Libur
                            |--------------------------------------------------------------------------
                            */

                            $isHoliday = $holidayDates->has(
                                $tanggal
                            );

                            /*
                            |--------------------------------------------------------------------------
                            | Hanya 1 Scan
                            |--------------------------------------------------------------------------
                            */

                            if ($dayLogs->count() < 2) {
                                return [
                                    'tanggal' => $tanggal,
                                    'jam_masuk' => $jamMasuk->format('H:i:s'),
                                    'jam_pulang' => null,
                                    'jam_masuk_jadwal' => $jamMasukJadwal,
                                    'jam_pulang_jadwal' => $jamPulangJadwal,
                                    'jam_bruto' => 0,
                                    'jam_istirahat' => $jamIstirahat,
                                    'jam_kerja' => 0,
                                    'jam_lembur' => 0,
                                    'jam_lembur_biasa' => 0,
                                    'jam_lembur_libur' => 0,
                                    'gaji_pokok' => 0,
                                    'total_lembur' => 0,
                                    'uang_makan_lembur' => 0,
                                    'jumlah_scan' => $dayLogs->count(),
                                    'is_holiday' => $isHoliday,
                                ];
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | Jam Pulang
                            |--------------------------------------------------------------------------
                            */

                            $jamPulang = Carbon::parse(
                                $last->punched_at
                            );

                            /*
                            |--------------------------------------------------------------------------
                            | Shift Melewati Tengah Malam
                            |--------------------------------------------------------------------------
                            */

                            if (
                                $jamPulang->lessThanOrEqualTo(
                                    $jamMasuk
                                )
                            ) {
                                $jamPulang->addDay();
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | Jam Bruto
                            |--------------------------------------------------------------------------
                            */

                            $jamBruto =
                                $jamMasuk->diffInMinutes(
                                    $jamPulang
                                ) / 60;

                            /*
                            |--------------------------------------------------------------------------
                            | Jam Kerja Aktual
                            |--------------------------------------------------------------------------
                            */

                            $jamKerja = max(
                                $jamBruto - $jamIstirahat,
                                0
                            );

                            /*
                            |--------------------------------------------------------------------------
                            | Jenis Karyawan Belum Ditentukan
                            |--------------------------------------------------------------------------
                            */

                            if (!$isValidType) {
                                return [
                                    'tanggal' => $tanggal,
                                    'jam_masuk' => $jamMasuk->format('H:i:s'),
                                    'jam_pulang' => $jamPulang->format('H:i:s'),
                                    'jam_masuk_jadwal' => $jamMasukJadwal,
                                    'jam_pulang_jadwal' => $jamPulangJadwal,
                                    'jam_bruto' => round($jamBruto, 2),
                                    'jam_istirahat' => $jamIstirahat,
                                    'jam_kerja' => round($jamKerja, 2),
                                    'jam_lembur' => 0,
                                    'jam_lembur_biasa' => 0,
                                    'jam_lembur_libur' => 0,
                                    'gaji_pokok' => 0,
                                    'total_lembur' => 0,
                                    'uang_makan_lembur' => 0,
                                    'jumlah_scan' => $dayLogs->count(),
                                    'is_holiday' => $isHoliday,
                                ];
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | BORONGAN
                            |--------------------------------------------------------------------------
                            |
                            | Tidak menggunakan lembur.
                            |
                            | Jika jam kerja aktual >= 10 jam:
                            | uang makan 1 kali pada hari tersebut.
                            |
                            */

                            if ($isBorongan) {
                                $hasil = $salaryService
                                    ->calculateBorongan(
                                        $jamMasuk->format('H:i:s'),
                                        $jamPulang->format('H:i:s'),
                                        $jamIstirahat,
                                        $tarifJam
                                    );

                                return [
                                    'tanggal' => $tanggal,
                                    'jam_masuk' => $jamMasuk->format('H:i:s'),
                                    'jam_pulang' => $jamPulang->format('H:i:s'),
                                    'jam_masuk_jadwal' => $jamMasukJadwal,
                                    'jam_pulang_jadwal' => $jamPulangJadwal,
                                    'jam_bruto' => $hasil['jam_bruto'],
                                    'jam_istirahat' => $hasil['jam_istirahat'],
                                    'jam_kerja' => $hasil['jam_kerja'],
                                    'jam_lembur' => 0,
                                    'jam_lembur_biasa' => 0,
                                    'jam_lembur_libur' => 0,
                                    'gaji_pokok' => $hasil['gaji_pokok'],
                                    'total_lembur' => 0,
                                    'uang_makan_lembur' => $hasil['uang_makan_lembur'],
                                    'jumlah_scan' => $dayLogs->count(),
                                    'is_holiday' => $isHoliday,
                                ];
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | HARIAN / BULANAN
                            |--------------------------------------------------------------------------
                            |
                            | DAPUR tidak mendapat lembur.
                            |
                            | Selain DAPUR:
                            | lembur mulai pada 8 jam 45 menit.
                            |
                            */

                            $hasil = $salaryService
                                ->calculateHarianBulanan(
                                    $tarifHarian,
                                    $jamKerja,
                                    $isHoliday,
                                    $isDapur,
                                    $schedule,
                                    $tanggal,
                                    $jamPulang->format('H:i:s')
                                );

                            return [
                                'tanggal' => $tanggal,
                                'jam_masuk' => $jamMasuk->format('H:i:s'),
                                'jam_pulang' => $jamPulang->format('H:i:s'),
                                'jam_masuk_jadwal' => $jamMasukJadwal,
                                'jam_pulang_jadwal' => $jamPulangJadwal,
                                'jam_bruto' => round($jamBruto, 2),
                                'jam_istirahat' => $jamIstirahat,
                                'jam_kerja' => round($jamKerja, 2),
                                'jam_lembur' => $hasil['jam_lembur'],
                                'jam_lembur_biasa' => $hasil['jam_lembur_biasa'],
                                'jam_lembur_libur' => $hasil['jam_lembur_libur'],
                                'gaji_pokok' => $hasil['gaji_pokok'],
                                'total_lembur' => $hasil['total_lembur'],
                                'uang_makan_lembur' => $hasil['uang_makan_lembur'],
                                'jumlah_scan' => $dayLogs->count(),
                                'is_holiday' => $isHoliday,
                            ];
                        }
                    )
                    ->filter()
                    ->values();

                /*
                |--------------------------------------------------------------------------
                | TAMBAHKAN HARI LIBUR YANG TIDAK ADA FINGERPRINT
                |--------------------------------------------------------------------------
                |
                | Contoh:
                | 5 Agustus sudah dimasukkan ke tabel holidays,
                | tetapi tidak ada scan fingerprint.
                |
                | Maka 5 Agustus tetap muncul di tabel absensi,
                | tetapi tidak dihitung sebagai hari hadir.
                |
                */

                $holidayOnlyDates = $holidayDates
                    ->keys()
                    ->diff(
                        $dailyAttendance->pluck('tanggal')
                    );

                foreach ($holidayOnlyDates as $tanggal) {
                    $dailyAttendance->push([
                        'tanggal' => $tanggal,
                        'jam_masuk' => null,
                        'jam_pulang' => null,
                        'jam_bruto' => 0,
                        'jam_istirahat' => $jamIstirahat,
                        'jam_kerja' => 0,
                        'jam_lembur' => 0,
                        'jam_lembur_biasa' => 0,
                        'jam_lembur_libur' => 0,
                        'gaji_pokok' => 0,
                        'total_lembur' => 0,
                        'uang_makan_lembur' => 0,
                        'jumlah_scan' => 0,
                        'is_holiday' => true,
                    ]);
                }

                $dailyAttendance = $dailyAttendance
                    ->sortBy('tanggal')
                    ->values();

                /*
                |--------------------------------------------------------------------------
                | Rekap Absensi
                |--------------------------------------------------------------------------
                |
                | Hari libur tanpa fingerprint memiliki jumlah_scan = 0,
                | sehingga tidak masuk hari hadir.
                |
                */

                $hariHadir = $dailyAttendance
                    ->filter(function ($day) {
                        return $day['jumlah_scan'] >= 2;
                    })
                    ->count();

                $jamKerja = round(
                    $dailyAttendance->sum('jam_kerja'),
                    2
                );

                $jamLembur = round(
                    $dailyAttendance->sum('jam_lembur'),
                    2
                );

                $jamLemburBiasa = round(
                    $dailyAttendance->sum('jam_lembur_biasa'),
                    2
                );

                $jamLemburLibur = round(
                    $dailyAttendance->sum('jam_lembur_libur'),
                    2
                );

                /*
                |--------------------------------------------------------------------------
                | Inisialisasi Gaji
                |--------------------------------------------------------------------------
                */

                $gajiPokok = 0;
                $totalLembur = 0;
                $uangMakanLembur = 0;
                $totalPotongan = 0;
                $gajiKotor = 0;
                $gajiBersih = 0;

                /*
                |--------------------------------------------------------------------------
                | Perhitungan Gaji
                |--------------------------------------------------------------------------
                */

                if ($isBorongan) {
                    $gajiPokok = round(
                        $dailyAttendance->sum('gaji_pokok'),
                        2
                    );

                    $totalLembur = 0;

                    $uangMakanLembur = round(
                        $dailyAttendance->sum('uang_makan_lembur'),
                        2
                    );

                    $totalPotongan = $salaryDetail
                        ? (float) $salaryDetail
                            ->deductions()
                            ->sum('nominal')
                        : 0;
                } elseif ($isHarian || $isBulanan) {
                    $gajiPokok = round(
                        $hariHadir * $tarifHarian,
                        2
                    );

                    $totalLembur = round(
                        $dailyAttendance->sum('total_lembur'),
                        2
                    );

                    $uangMakanLembur = round(
                        $dailyAttendance->sum('uang_makan_lembur'),
                        2
                    );

                    $totalPotongan = $salaryDetail
                        ? (float) $salaryDetail
                            ->deductions()
                            ->sum('nominal')
                        : 0;
                }

                /*
                |--------------------------------------------------------------------------
                | Gaji Kotor & Bersih
                |--------------------------------------------------------------------------
                */

                $gajiKotor = round(
                    $gajiPokok
                    + $totalLembur
                    + $uangMakanLembur,
                    2
                );

                $gajiBersih = max(
                    $gajiKotor - $totalPotongan,
                    0
                );

                return [
                    'employee' => $employee,
                    'attendance' => $dailyAttendance,
                    'salary_detail' => $salaryDetail,
                    'hari_hadir' => $hariHadir,
                    'jam_kerja' => $jamKerja,
                    'jam_lembur' => $jamLembur,
                    'jam_lembur_biasa' => $jamLemburBiasa,
                    'jam_lembur_libur' => $jamLemburLibur,
                    'tarif_harian' => $tarifHarian,
                    'tarif_jam' => $tarifJam,
                    'gaji_pokok' => $gajiPokok,
                    'total_lembur' => $totalLembur,
                    'uang_makan_lembur' => $uangMakanLembur,
                    'total_potongan' => $totalPotongan,
                    'gaji_kotor' => $gajiKotor,
                    'gaji_bersih' => $gajiBersih,
                    'jenis_karyawan_kosong' => !$isValidType,
                ];
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Data Dropdown
        |--------------------------------------------------------------------------
        */

        $bagianList = Employee::query()
            ->where('is_active', true)
            ->whereNotNull('bagian')
            ->where('bagian', '!=', '')
            ->distinct()
            ->orderBy('bagian')
            ->pluck('bagian');

        $jenisKaryawanList = Employee::query()
            ->where('is_active', true)
            ->whereNotNull('jenis_karyawan')
            ->where('jenis_karyawan', '!=', '')
            ->distinct()
            ->orderBy('jenis_karyawan')
            ->pluck('jenis_karyawan');

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'salary.index',
            compact(
                'employees',
                'salaryEmployees',
                'tanggalMulai',
                'tanggalSelesai',
                'bagianList',
                'jenisKaryawanList'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL GAJI
    |--------------------------------------------------------------------------
    */

    public function detail(
        Request $request,
        Employee $employee,
        SalaryCalculationService $salaryService
    ) {
        $tanggalMulai = $request->input(
            'tanggal_mulai',
            now()->startOfMonth()->format('Y-m-d')
        );

        $tanggalSelesai = $request->input(
            'tanggal_selesai',
            now()->endOfMonth()->format('Y-m-d')
        );

        $startDate = Carbon::parse($tanggalMulai)->startOfDay();
        $endDate = Carbon::parse($tanggalSelesai)->endOfDay();

        /*
        |--------------------------------------------------------------------------
        | Periode Gaji
        |--------------------------------------------------------------------------
        */

        $period = SalaryPeriod::query()
            ->whereDate(
                'tanggal_mulai',
                $startDate->toDateString()
            )
            ->whereDate(
                'tanggal_selesai',
                $endDate->toDateString()
            )
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Detail Pengaturan Gaji
        |--------------------------------------------------------------------------
        */

        $salaryDetail = null;

        if ($period) {
            $salaryDetail = SalaryDetail::query()
                ->where(
                    'salary_period_id',
                    $period->id
                )
                ->where(
                    'employee_id',
                    $employee->id
                )
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Jenis Karyawan
        |--------------------------------------------------------------------------
        */

        $jenisKaryawan = strtolower(
            trim(
                (string) ($employee->jenis_karyawan ?? '')
            )
        );

        $isBorongan = $jenisKaryawan === 'borongan';
        $isHarian = $jenisKaryawan === 'harian';
        $isBulanan = $jenisKaryawan === 'bulanan';

        $isDapur = strtoupper(
            trim(
                (string) ($employee->bagian ?? '')
            )
        ) === 'DAPUR';

        $isValidType =
            $isBorongan ||
            $isHarian ||
            $isBulanan;

        /*
        |--------------------------------------------------------------------------
        | Tarif
        |--------------------------------------------------------------------------
        */

        if ($isHarian) {
            $tarifHarian = $this->salarySetting('harian_per_hari', 127855);
        } elseif ($isBulanan) {
            $tarifHarian = $salaryDetail
                ? (float) $salaryDetail->tarif_harian
                : 0;
        } else {
            $tarifHarian = 0;
        }

        $tarifJam = $isBorongan
            ? $this->salarySetting('borongan_per_jam', 18000)
            : 0;

        $jamIstirahat = $salaryDetail
            ? (float) $salaryDetail->jam_istirahat
            : 1;

        /*
        |--------------------------------------------------------------------------
        | Ambil Data Fingerprint
        |--------------------------------------------------------------------------
        */

        $attendanceLogs = AttendanceLog::query()
            ->where(
                'pin',
                $employee->fingerprint_pin
            )
            ->whereBetween(
                'punched_at',
                [
                    $startDate,
                    $endDate,
                ]
            )
            ->orderBy('punched_at')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Kalender Hari Libur
        |--------------------------------------------------------------------------
        */

        $holidayDates = Holiday::query()
            ->whereBetween(
                'tanggal',
                [
                    $startDate->toDateString(),
                    $endDate->toDateString(),
                ]
            )
            ->pluck('tanggal')
            ->map(function ($tanggal) {
                return Carbon::parse($tanggal)
                    ->format('Y-m-d');
            })
            ->flip();

        /*
        |--------------------------------------------------------------------------
        | Kelompokkan Absensi
        |--------------------------------------------------------------------------
        */

        $attendance = $this->groupAttendanceByShiftDate($attendanceLogs)
            ->map(
                function ($dayLogs, $tanggalShift) use (
                    $salaryService,
                    $tarifHarian,
                    $tarifJam,
                    $jamIstirahat,
                    $holidayDates,
                    $isBorongan,
                    $isDapur,
                    $isValidType,
                    $employee
                ) {
                    $dayLogs = $dayLogs
                        ->sortBy('punched_at')
                        ->values();

                    $first = $dayLogs->first();
                    $last = $dayLogs->last();

                    if (!$first) {
                        return null;
                    }

                    $jamMasuk = Carbon::parse(
                        $first->punched_at
                    );

                    $tanggal = $tanggalShift;

                    // Jadwal khusus karyawan diprioritaskan, lalu jadwal harian berdasarkan penempatan.
                    $schedule = $this->getWorkSchedule(
                        $employee->id,
                        $tanggal
                    );
                    $jamMasukJadwal = $schedule?->jam_masuk;
                    $jamPulangJadwal = $schedule?->jam_pulang;

                    /*
                    |--------------------------------------------------------------------------
                    | Cek Hari Libur
                    |--------------------------------------------------------------------------
                    */

                    $isHoliday = $holidayDates->has(
                        $tanggal
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Hanya 1 Scan
                    |--------------------------------------------------------------------------
                    */

                    if ($dayLogs->count() < 2) {
                        return [
                            'tanggal' => $tanggal,
                            'jam_masuk' => $jamMasuk->format('H:i:s'),
                            'jam_pulang' => null,
                            'jam_masuk_jadwal' => $jamMasukJadwal,
                            'jam_pulang_jadwal' => $jamPulangJadwal,
                            'jam_bruto' => 0,
                            'jam_istirahat' => $jamIstirahat,
                            'jam_kerja' => 0,
                            'jam_lembur' => 0,
                            'jam_lembur_biasa' => 0,
                            'jam_lembur_libur' => 0,
                            'gaji_pokok' => 0,
                            'total_lembur' => 0,
                            'uang_makan_lembur' => 0,
                            'jumlah_scan' => $dayLogs->count(),
                            'is_holiday' => $isHoliday,
                            'logs' => $dayLogs,
                        ];
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Jam Pulang
                    |--------------------------------------------------------------------------
                    */

                    $jamPulang = Carbon::parse(
                        $last->punched_at
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Shift Melewati Tengah Malam
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $jamPulang->lessThanOrEqualTo(
                            $jamMasuk
                        )
                    ) {
                        $jamPulang->addDay();
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Jam Bruto
                    |--------------------------------------------------------------------------
                    */

                    $jamBruto =
                        $jamMasuk->diffInMinutes(
                            $jamPulang
                        ) / 60;

                    /*
                    |--------------------------------------------------------------------------
                    | Jam Kerja Aktual
                    |--------------------------------------------------------------------------
                    */

                    $jamKerja = max(
                        $jamBruto - $jamIstirahat,
                        0
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Jenis Karyawan Kosong
                    |--------------------------------------------------------------------------
                    */

                    if (!$isValidType) {
                        return [
                            'tanggal' => $tanggal,
                            'jam_masuk' => $jamMasuk->format('H:i:s'),
                            'jam_pulang' => $jamPulang->format('H:i:s'),
                            'jam_masuk_jadwal' => $jamMasukJadwal,
                            'jam_pulang_jadwal' => $jamPulangJadwal,
                            'jam_bruto' => round($jamBruto, 2),
                            'jam_istirahat' => $jamIstirahat,
                            'jam_kerja' => round($jamKerja, 2),
                            'jam_lembur' => 0,
                            'jam_lembur_biasa' => 0,
                            'jam_lembur_libur' => 0,
                            'gaji_pokok' => 0,
                            'total_lembur' => 0,
                            'uang_makan_lembur' => 0,
                            'jumlah_scan' => $dayLogs->count(),
                            'is_holiday' => $isHoliday,
                            'logs' => $dayLogs,
                        ];
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | BORONGAN
                    |--------------------------------------------------------------------------
                    */

                    if ($isBorongan) {
                        $hasil = $salaryService
                            ->calculateBorongan(
                                $jamMasuk->format('H:i:s'),
                                $jamPulang->format('H:i:s'),
                                $jamIstirahat,
                                $tarifJam
                            );

                        return [
                            'tanggal' => $tanggal,
                            'jam_masuk' => $jamMasuk->format('H:i:s'),
                            'jam_pulang' => $jamPulang->format('H:i:s'),
                            'jam_masuk_jadwal' => $jamMasukJadwal,
                            'jam_pulang_jadwal' => $jamPulangJadwal,
                            'jam_bruto' => $hasil['jam_bruto'],
                            'jam_istirahat' => $hasil['jam_istirahat'],
                            'jam_kerja' => $hasil['jam_kerja'],
                            'jam_lembur' => 0,
                            'jam_lembur_biasa' => 0,
                            'jam_lembur_libur' => 0,
                            'gaji_pokok' => $hasil['gaji_pokok'],
                            'total_lembur' => 0,
                            'uang_makan_lembur' => $hasil['uang_makan_lembur'],
                            'jumlah_scan' => $dayLogs->count(),
                            'is_holiday' => $isHoliday,
                            'logs' => $dayLogs,
                        ];
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | HARIAN / BULANAN
                    |--------------------------------------------------------------------------
                    */

                    $hasil = $salaryService
                        ->calculateHarianBulanan(
                            $tarifHarian,
                            $jamKerja,
                            $isHoliday,
                            $isDapur,
                            $schedule,
                            $tanggal,
                            $jamPulang->format('H:i:s')
                        );

                    return [
                        'tanggal' => $tanggal,
                        'jam_masuk' => $jamMasuk->format('H:i:s'),
                        'jam_pulang' => $jamPulang->format('H:i:s'),
                        'jam_masuk_jadwal' => $jamMasukJadwal,
                        'jam_pulang_jadwal' => $jamPulangJadwal,
                        'jam_bruto' => round($jamBruto, 2),
                        'jam_istirahat' => $jamIstirahat,
                        'jam_kerja' => round($jamKerja, 2),
                        'jam_lembur' => $hasil['jam_lembur'],
                        'jam_lembur_biasa' => $hasil['jam_lembur_biasa'],
                        'jam_lembur_libur' => $hasil['jam_lembur_libur'],
                        'gaji_pokok' => $hasil['gaji_pokok'],
                        'total_lembur' => $hasil['total_lembur'],
                        'uang_makan_lembur' => $hasil['uang_makan_lembur'],
                        'jumlah_scan' => $dayLogs->count(),
                        'is_holiday' => $isHoliday,
                        'logs' => $dayLogs,
                    ];
                }
            )
            ->filter()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | TAMBAHKAN HARI LIBUR TANPA FINGERPRINT
        |--------------------------------------------------------------------------
        */

        $holidayOnlyDates = $holidayDates
            ->keys()
            ->diff(
                $attendance->pluck('tanggal')
            );

        foreach ($holidayOnlyDates as $tanggal) {
            $attendance->push([
                'tanggal' => $tanggal,
                'jam_masuk' => null,
                'jam_pulang' => null,
                'jam_bruto' => 0,
                'jam_istirahat' => $jamIstirahat,
                'jam_kerja' => 0,
                'jam_lembur' => 0,
                'jam_lembur_biasa' => 0,
                'jam_lembur_libur' => 0,
                'gaji_pokok' => 0,
                'total_lembur' => 0,
                'uang_makan_lembur' => 0,
                'jumlah_scan' => 0,
                'is_holiday' => true,
                'logs' => collect(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Urutkan Berdasarkan Tanggal
        |--------------------------------------------------------------------------
        */

        $attendance = $attendance
            ->sortBy('tanggal')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Rekap
        |--------------------------------------------------------------------------
        */

        $hariHadir = $attendance
            ->filter(function ($day) {
                return !empty($day['jam_pulang'])
                    && ($day['jam_kerja'] ?? 0) > 0;
            })
            ->count();

        $jamKerja = round(
            $attendance->sum('jam_kerja'),
            2
        );

        $jamLembur = round(
            $attendance->sum('jam_lembur'),
            2
        );

        $jamLemburBiasa = round(
            $attendance->sum('jam_lembur_biasa'),
            2
        );

        $jamLemburLibur = round(
            $attendance->sum('jam_lembur_libur'),
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Inisialisasi Gaji
        |--------------------------------------------------------------------------
        */

        $gajiPokok = 0;
        $totalLembur = 0;
        $uangMakanLembur = 0;
        $totalPotongan = 0;
        $gajiKotor = 0;
        $gajiBersih = 0;

        /*
        |--------------------------------------------------------------------------
        | Gaji Pokok & Lembur
        |--------------------------------------------------------------------------
        */

        if ($isBorongan) {
            $gajiPokok = round(
                $attendance->sum('gaji_pokok'),
                2
            );

            $totalLembur = 0;

            $uangMakanLembur = round(
                $attendance->sum('uang_makan_lembur'),
                2
            );
        } elseif ($isHarian || $isBulanan) {
            $gajiPokok = round(
                $hariHadir * $tarifHarian,
                2
            );

            $totalLembur = round(
                $attendance->sum('total_lembur'),
                2
            );

            $uangMakanLembur = round(
                $attendance->sum('uang_makan_lembur'),
                2
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Potongan
        |--------------------------------------------------------------------------
        */

        $deductions = $salaryDetail
            ? $salaryDetail
                ->deductions()
                ->orderBy('id')
                ->get()
            : collect();

        if ($isValidType) {
            $totalPotongan = (float) $deductions->sum('nominal');
        } else {
            $totalPotongan = 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Gaji Kotor & Bersih
        |--------------------------------------------------------------------------
        */

        $gajiKotor = round(
            $gajiPokok
            + $totalLembur
            + $uangMakanLembur,
            2
        );

        $gajiBersih = max(
            $gajiKotor - $totalPotongan,
            0
        );

        /*
        |--------------------------------------------------------------------------
        | Rekap Gaji
        |--------------------------------------------------------------------------
        */

        $salarySummary = [
            'hari_hadir' => $hariHadir,
            'jam_kerja' => $jamKerja,
            'jam_lembur' => $jamLembur,
            'jam_lembur_biasa' => $jamLemburBiasa,
            'jam_lembur_libur' => $jamLemburLibur,
            'gaji_pokok' => $gajiPokok,
            'total_lembur' => $totalLembur,
            'uang_makan_lembur' => $uangMakanLembur,
            'total_potongan' => $totalPotongan,
            'gaji_kotor' => $gajiKotor,
            'gaji_bersih' => $gajiBersih,
        ];


return view(
    'salary.detail',
    compact(
        'employee',
        'salaryDetail',
        'tanggalMulai',
        'tanggalSelesai',
        'attendance',
        'salarySummary',
        'deductions',
        'tarifHarian',
        'tarifJam'
    )
);

    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN DETAIL GAJI
    |--------------------------------------------------------------------------
    */

    public function updateDetail(
        Request $request,
        Employee $employee
    ) {
        $data = $request->validate([
            'tanggal_mulai' => [
                'required',
                'date',
            ],

            'tanggal_selesai' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],

            'tarif_harian' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'tarif_jam' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'jam_istirahat' => [
                'nullable',
                'numeric',
                'min:0',
                'max:24',
            ],

            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Periode
        |--------------------------------------------------------------------------
        */

        $period = SalaryPeriod::firstOrCreate(
            [
                'tanggal_mulai' => $data['tanggal_mulai'],
                'tanggal_selesai' => $data['tanggal_selesai'],
            ],
            [
                'status' => 'draft',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Detail Gaji
        |--------------------------------------------------------------------------
        */

        $detail = SalaryDetail::firstOrNew([
            'salary_period_id' => $period->id,
            'employee_id' => $employee->id,
        ]);

        $detail->jenis_karyawan =
            $employee->jenis_karyawan;

        $jenisKaryawan = strtolower(
            trim(
                (string) ($employee->jenis_karyawan ?? '')
            )
        );

        if ($jenisKaryawan === 'harian') {
            $detail->tarif_harian = $this->salarySetting('harian_per_hari', 127855);
            $detail->tarif_jam = 0;
        } elseif ($jenisKaryawan === 'bulanan') {
            $detail->tarif_harian =
                (float) ($data['tarif_harian'] ?? 0);

            $detail->tarif_jam = 0;
        } elseif ($jenisKaryawan === 'borongan') {
            $detail->tarif_harian = 0;
            $detail->tarif_jam = $this->salarySetting('borongan_per_jam', 18000);
        } else {
            $detail->tarif_harian = 0;
            $detail->tarif_jam = 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Tarif Lembur
        |--------------------------------------------------------------------------
        */

        $detail->tarif_lembur_biasa = $this->salarySetting('lembur_biasa_per_jam', 18500);
        $detail->tarif_lembur_libur = $this->salarySetting('lembur_libur_per_jam', 37000);
        $detail->tarif_uang_makan = $this->salarySetting('uang_makan_per_3_jam', 11000);

        /*
        |--------------------------------------------------------------------------
        | Istirahat
        |--------------------------------------------------------------------------
        */

        $detail->jam_istirahat =
            $data['jam_istirahat'] ?? 1;

        $detail->catatan =
            $data['keterangan'] ?? null;

        $detail->save();

        $this->syncTotalPotongan($detail);

        return redirect()
            ->route(
                'salary.detail',
                array_merge(
                    [
                        'employee' => $employee->id,
                    ],
                    $this->filterParams($request)
                )
            )
            ->with(
                'success',
                'Pengaturan gaji berhasil disimpan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | PARAMETER FILTER
    |--------------------------------------------------------------------------
    */

    private function filterParams(
        Request $request
    ): array {
        return array_filter([
            'tanggal_mulai' =>
                $request->input('tanggal_mulai'),

            'tanggal_selesai' =>
                $request->input('tanggal_selesai'),

            'jenis_karyawan' =>
                $request->input('jenis_karyawan'),

            'bagian' =>
                $request->input('bagian'),

            'search' =>
                $request->input('search'),
        ], function ($value) {
            return $value !== null
                && $value !== '';
        });
    }

    /*
    |--------------------------------------------------------------------------
    | TAMBAH POTONGAN
    |--------------------------------------------------------------------------
    */

    public function storeDeduction(
        Request $request,
        Employee $employee
    ) {
        $validated = $request->validate([
            'tanggal_mulai' => [
                'required',
                'date',
            ],

            'tanggal_selesai' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],

            'nama_potongan' => [
                'required',
                'string',
                'max:255',
            ],

            'nominal' => [
                'required',
                'numeric',
                'min:0',
            ],

            'keterangan_potongan' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Periode
        |--------------------------------------------------------------------------
        */

        $period = SalaryPeriod::firstOrCreate(
            [
                'tanggal_mulai' =>
                    $validated['tanggal_mulai'],

                'tanggal_selesai' =>
                    $validated['tanggal_selesai'],
            ],
            [
                'status' => 'draft',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Detail Gaji
        |--------------------------------------------------------------------------
        */

        $jenisKaryawan = strtolower(
            trim(
                (string) ($employee->jenis_karyawan ?? '')
            )
        );

        $defaultTarifHarian =
            $jenisKaryawan === 'harian'
                ? $this->salarySetting('harian_per_hari', 127855)
                : 0;

        $defaultTarifJam =
            $jenisKaryawan === 'borongan'
                ? $this->salarySetting('borongan_per_jam', 18000)
                : 0;

        $salaryDetail = SalaryDetail::firstOrCreate(
            [
                'salary_period_id' => $period->id,
                'employee_id' => $employee->id,
            ],
            [
                'jenis_karyawan' =>
                    $employee->jenis_karyawan,

                'tarif_harian' =>
                    $defaultTarifHarian,

                'tarif_jam' =>
                    $defaultTarifJam,

                'tarif_lembur_biasa' =>
                    $this->salarySetting('lembur_biasa_per_jam', 18500),

                'tarif_lembur_libur' =>
                    $this->salarySetting('lembur_libur_per_jam', 37000),

                'tarif_uang_makan' =>
                    $this->salarySetting('uang_makan_per_3_jam', 11000),

                'jam_istirahat' =>
                    1,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Simpan Potongan
        |--------------------------------------------------------------------------
        */

        $salaryDetail->deductions()->create([
            'nama_potongan' =>
                $validated['nama_potongan'],

            'nominal' =>
                $validated['nominal'],

            'keterangan' =>
                $validated['keterangan_potongan'] ?? null,
        ]);

        $this->syncTotalPotongan($salaryDetail);

        return redirect()
            ->route(
                'salary.detail',
                array_merge(
                    [
                        'employee' => $employee->id,
                    ],
                    $this->filterParams($request)
                )
            )
            ->with(
                'success',
                'Potongan gaji berhasil ditambahkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE POTONGAN
    |--------------------------------------------------------------------------
    */

    public function updateDeduction(
        Request $request,
        Employee $employee,
        SalaryDeduction $deduction
    ) {
        $validated = $request->validate([
            'tanggal_mulai' => [
                'required',
                'date',
            ],

            'tanggal_selesai' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],

            'nama_potongan' => [
                'required',
                'string',
                'max:255',
            ],

            'nominal' => [
                'required',
                'numeric',
                'min:0',
            ],

            'keterangan_potongan' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pastikan Potongan Milik Karyawan
        |--------------------------------------------------------------------------
        */

        if (
            !$deduction->detail ||
            $deduction->detail->employee_id !== $employee->id
        ) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $deduction->update([
            'nama_potongan' =>
                $validated['nama_potongan'],

            'nominal' =>
                $validated['nominal'],

            'keterangan' =>
                $validated['keterangan_potongan'] ?? null,
        ]);

        $this->syncTotalPotongan(
            $deduction->detail
        );

        return redirect()
            ->route(
                'salary.detail',
                array_merge(
                    [
                        'employee' => $employee->id,
                    ],
                    $this->filterParams($request)
                )
            )
            ->with(
                'success',
                'Potongan gaji berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS POTONGAN
    |--------------------------------------------------------------------------
    */

    public function destroyDeduction(
        Request $request,
        Employee $employee,
        SalaryDeduction $deduction
    ) {
        /*
        |--------------------------------------------------------------------------
        | Pastikan Potongan Milik Karyawan
        |--------------------------------------------------------------------------
        */

        if (
            !$deduction->detail ||
            $deduction->detail->employee_id !== $employee->id
        ) {
            abort(404);
        }

        $salaryDetail = $deduction->detail;

        $deduction->delete();

        $this->syncTotalPotongan(
            $salaryDetail
        );

        return redirect()
            ->route(
                'salary.detail',
                array_merge(
                    [
                        'employee' => $employee->id,
                    ],
                    $this->filterParams($request)
                )
            )
            ->with(
                'success',
                'Potongan gaji berhasil dihapus.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SYNC TOTAL POTONGAN
    |--------------------------------------------------------------------------
    */

    private function syncTotalPotongan(
        SalaryDetail $salaryDetail
    ): void {
        $totalPotongan = $salaryDetail
            ->deductions()
            ->sum('nominal');

        $salaryDetail->update([
            'total_potongan' => $totalPotongan,

            'gaji_bersih' => max(
                (float) $salaryDetail->gaji_kotor
                - $totalPotongan,
                0
            ),
        ]);
    }

/**
 * Group fingerprint scans by shift date and attach an early-morning checkout
 * to the previous date when that previous date has exactly one scan and the
 * current date has an early scan followed by at least two scans for its own shift.
 * This avoids mixing 00:48 checkout with the next day's 07:49/15:45 pair.
 */
private function groupAttendanceByShiftDate($logs)
{
    $daily = $logs
        ->sortBy('punched_at')
        ->groupBy(function ($log) {
            return Carbon::parse($log->punched_at)->format('Y-m-d');
        })
        ->map(function ($dayLogs) {
            return $dayLogs->sortBy('punched_at')->values();
        });

    $dates = $daily->keys()->sort()->values();

    foreach ($dates as $date) {
        $current = $daily->get($date);
        if (!$current || $current->count() < 3) {
            continue;
        }

        $first = $current->first();
        $firstTime = Carbon::parse($first->punched_at);
        if ($firstTime->hour >= 4) {
            continue;
        }

        $previousDate = $firstTime->copy()->subDay()->format('Y-m-d');
        $previous = $daily->get($previousDate);
        if (!$previous || $previous->count() !== 1) {
            continue;
        }

        $previousTime = Carbon::parse($previous->first()->punched_at);
        $elapsedMinutes = $previousTime->diffInMinutes($firstTime);
        if ($elapsedMinutes <= 0 || $elapsedMinutes > 22 * 60) {
            continue;
        }

        $daily->put(
            $previousDate,
            $previous->push($first)->sortBy('punched_at')->values()
        );
        $daily->put($date, $current->slice(1)->values());
    }

    return $daily->sortKeys();
}

private function salarySetting(string $key, float $default): float
{
    // Kolom tarif pada tabel salary_settings bernama "amount", bukan "value".
    // Gunakan helper model agar tarif yang tersimpan benar-benar terbaca.
    $value = SalarySetting::value($key, $default);

    return $value > 0 ? $value : $default;
}

private function getWorkSchedule(
    int $employeeId,
    string $tanggal
): ?object {
    $employee = Employee::find($employeeId);

    if (!$employee) {
        return null;
    }

    // Prioritas 1: jadwal khusus untuk karyawan dan tanggal tersebut.
    $jadwalKhusus = EmployeeWorkSchedule::with('workSchedule')
        ->where('employee_id', $employeeId)
        ->whereDate('tanggal', $tanggal)
        ->first();

    if ($jadwalKhusus?->workSchedule) {
        return $jadwalKhusus->workSchedule;
    }

    // Prioritas 2: jadwal harian berdasarkan unit penempatan dan tanggal.
    if (empty($employee->penempatan)) {
        return null;
    }

    return DailyWorkSchedule::with('workSchedule')
        ->whereDate('tanggal', $tanggal)
        ->where('penempatan', $employee->penempatan)
        ->first()?->workSchedule;
}

private function buildPunchDateTime(
    string $tanggal,
    $time,
    ?Carbon $reference = null
): ?Carbon {
    if (!$time) {
        return null;
    }

    $time = Carbon::parse($time);

    $result = Carbon::parse(
        $tanggal . ' ' . $time->format('H:i:s')
    );

    /*
     * Kalau ada waktu sebelumnya dan hasil scan
     * ternyata lebih kecil, berarti scan terjadi
     * setelah tengah malam.
     */
    if ($reference && $result->lessThan($reference)) {
        $result->addDay();
    }

    return $result;
}
private function calculatePunchDuration(
    string $tanggal,
    $jamMasuk,
    $jamPulang
): array {
    if (!$jamMasuk || !$jamPulang) {
        return [
            'masuk' => null,
            'pulang' => null,
            'menit' => 0,
            'jam' => 0,
        ];
    }

    $masuk = Carbon::parse(
        $tanggal . ' ' . Carbon::parse($jamMasuk)->format('H:i:s')
    );

    $pulang = Carbon::parse(
        $tanggal . ' ' . Carbon::parse($jamPulang)->format('H:i:s')
    );

    /*
     * Pulang lebih kecil dari masuk =
     * pulang pada hari berikutnya.
     */
    if ($pulang->lessThan($masuk)) {
        $pulang->addDay();
    }

    $menit = $masuk->diffInMinutes($pulang);

    return [
        'masuk' => $masuk,
        'pulang' => $pulang,
        'menit' => $menit,
        'jam' => $menit / 60,
    ];
}
public function archive(
    Request $request,
    SalaryCalculationService $salaryService
) {
    $request->validate([
        'tanggal_mulai' => ['required', 'date'],
        'tanggal_selesai' => [
            'required',
            'date',
            'after_or_equal:tanggal_mulai',
        ],
        'jenis_karyawan' => ['nullable', 'string'],
        'bagian' => ['nullable', 'string'],
        'search' => ['nullable', 'string', 'max:255'],
    ]);

    $view = $this->index($request, $salaryService);
    $data = $view->getData();

    $salaryEmployees = $data['salaryEmployees'];
    $tanggalMulai = $data['tanggalMulai'];
    $tanggalSelesai = $data['tanggalSelesai'];

    if ($salaryEmployees->isEmpty()) {
        return redirect()
            ->route('salary.index', $request->only([
                'tanggal_mulai',
                'tanggal_selesai',
                'jenis_karyawan',
                'bagian',
                'search',
            ]))
            ->with('error', 'Tidak ada data gaji untuk diarsipkan.');
    }

    DB::transaction(function () use (
        $salaryEmployees,
        $tanggalMulai,
        $tanggalSelesai,
        $request
    ) {
        $totalGaji = $salaryEmployees->sum(
            fn ($row) => (float) $row['gaji_bersih']
        );

        $jenisKaryawan = trim(
            (string) $request->input('jenis_karyawan', '')
        );

        $bagian = trim(
            (string) $request->input('bagian', '')
        );

        $search = trim(
            (string) $request->input('search', '')
        );

        $namaJenis = $jenisKaryawan !== ''
            ? ucwords(strtolower($jenisKaryawan))
            : 'Semua Karyawan';

        $namaBagian = $bagian !== ''
            ? ucwords(strtolower($bagian))
            : 'Semua Bagian';

        $namaArsip = 'Gaji ' . $namaJenis . ' - ' . $namaBagian;

        if ($search !== '') {
            $namaArsip .= ' - ' . $search;
        }

        $namaArsip .= ' - ' . $tanggalMulai
            . ' s.d. ' . $tanggalSelesai;

        $archive = SalaryArchive::create([
            'archive_name' => $namaArsip,
            'start_date' => $tanggalMulai,
            'end_date' => $tanggalSelesai,
            'total_salary' => $totalGaji,
            'total_checklogs' => $salaryEmployees->count(),
            'total_employees' => $salaryEmployees->count(),
            'archive_type' => 'salary',
            'filters' => [
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'jenis_karyawan' => $request->input('jenis_karyawan'),
                'bagian' => $request->input('bagian'),
                'search' => $request->input('search'),
            ],
        ]);

        foreach ($salaryEmployees as $row) {
            $employee = $row['employee'];

            /*
             * Cari detail gaji berdasarkan karyawan.
             * Jika row sudah membawa salary_detail, gunakan data itu.
             */
            $salaryDetail = $row['salary_detail'] ?? null;

            /*
             * Jika data detail belum ada pada row, cari berdasarkan
             * employee_id dan periode yang digunakan oleh detail tersebut.
             */
            if (!$salaryDetail) {
                $salaryDetail = SalaryDetail::query()
                    ->where('employee_id', $employee->id)
                    ->whereHas('period', function ($query) use (
                        $tanggalMulai,
                        $tanggalSelesai
                    )
            {
                        $query->whereDate(
                            'tanggal_mulai',
                            $tanggalMulai
                        )->whereDate(
                            'tanggal_selesai',
                            $tanggalSelesai
                        );
                    })
                    ->latest('id')
                    ->first();
            }
                        $rincianPotongan = [];

                        if ($salaryDetail) {
                        $rincianPotongan = $salaryDetail
                        ->deductions()
                        ->orderBy('id')
                        ->get([
                            'nama_potongan',
                            'nominal',
                            'keterangan',
                        ])
                        ->map(function ($potongan) {
                            return [
                                'nama_potongan' => $potongan->nama_potongan,
                                'nominal' => (float) $potongan->nominal,
                                'keterangan' => $potongan->keterangan,
                            ];
                        })
                        ->values()
                        ->all();
                }    

                SalaryArchiveDetail::create([
                    'salary_archive_id' => $archive->id,
                    'employee_id' => $employee->id,
                    'fingerprint_pin' => $employee->fingerprint_pin,
                    'employee_name' => $employee->name,
                    'bagian' => $employee->bagian,
                    'jenis_karyawan' => $employee->jenis_karyawan,

                    'hari_hadir' => $row['hari_hadir'],
                    'jam_kerja' => $row['jam_kerja'],
                    'jam_lembur' => $row['jam_lembur'],
                    'jam_lembur_biasa' => $row['jam_lembur_biasa'],
                    'jam_lembur_libur' => $row['jam_lembur_libur'],

                    'tarif_harian' => $row['tarif_harian'],
                    'tarif_jam' => $row['tarif_jam'],
                    'gaji_pokok' => $row['gaji_pokok'],
                    'total_lembur' => $row['total_lembur'],
                    'uang_makan_lembur' => $row['uang_makan_lembur'],

                    'total_potongan' => $row['total_potongan'],
                    'rincian_potongan' => $rincianPotongan,

                    'gaji_kotor' => $row['gaji_kotor'],
                    'gaji_bersih' => $row['gaji_bersih'],

                    'daily_breakdown' => collect($row['attendance'] ?? [])
                        ->map(function ($day) {
                            return [
                                'tanggal' => $day['tanggal'] ?? null,
                                'jam_kerja' => (float) ($day['jam_kerja'] ?? 0),
                                'jam_lembur' => (float) ($day['jam_lembur'] ?? 0),
                                'jam_lembur_biasa' => (float) ($day['jam_lembur_biasa'] ?? 0),
                                'jam_lembur_libur' => (float) ($day['jam_lembur_libur'] ?? 0),
                                'gaji_pokok' => (float) ($day['gaji_pokok'] ?? 0),
                                'total_lembur' => (float) ($day['total_lembur'] ?? 0),
                                'uang_makan_lembur' => (float) ($day['uang_makan_lembur'] ?? 0),
                            ];
                        })
                        ->values()
                        ->all(),
                ]);
            }
        });

        return redirect()
            ->route('salary_archives.index')
            ->with('success', 'Arsip gaji berhasil disimpan.');
    }
    
public function toggleBpjs(Request $request, Employee $employee)
{
    $data = $request->validate([
        'tanggal_mulai' => ['required', 'date'],
        'tanggal_selesai' => [
            'required',
            'date',
            'after_or_equal:tanggal_mulai',
        ],
        'potong_bpjs' => ['nullable', 'boolean'],
    ]);

    $potongBpjs = $request->boolean('potong_bpjs');

    // Tarif gabungan diambil dari Master BPJS.
    $terdaftarBpjs =
        $employee->bpjs_tk_terdaftar ||
        $employee->bpjs_kesehatan_terdaftar;

    $tarifBpjs = (float) ($employee->tarif_bpjs ?? 0);

    if ($potongBpjs && (!$terdaftarBpjs || $tarifBpjs <= 0)) {
        return redirect()
            ->route('salary.index', $this->filterParams($request))
            ->with(
                'error',
                'Karyawan belum terdaftar BPJS atau tarif BPJS belum diisi.'
            );
    }

    $period = SalaryPeriod::firstOrCreate(
        [
            'tanggal_mulai' => $data['tanggal_mulai'],
            'tanggal_selesai' => $data['tanggal_selesai'],
        ],
        ['status' => 'draft']
    );

    $jenisKaryawan = strtolower(
        trim((string) ($employee->jenis_karyawan ?? ''))
    );

    // Buat detail hanya jika belum tersedia.
    // Detail yang sudah ada tidak diubah tarifnya.
    $detail = SalaryDetail::firstOrNew([
        'salary_period_id' => $period->id,
        'employee_id' => $employee->id,
    ]);

    if (!$detail->exists) {
        $detail->jenis_karyawan = $employee->jenis_karyawan;
        $detail->tarif_harian = $jenisKaryawan === 'harian'
            ? 127855
            : 0;
        $detail->tarif_jam = $jenisKaryawan === 'borongan'
            ? 18000
            : 0;
        $detail->tarif_lembur_biasa = 18500;
        $detail->tarif_lembur_libur = 37000;
        $detail->tarif_uang_makan = 11000;
        $detail->jam_istirahat = 1;
        $detail->save();
    }

    // Penanda khusus agar potongan manual tidak ikut terhapus.
    $namaPotongan = 'Potongan BPJS';
    $penandaOtomatis = 'Potongan otomatis dari Master BPJS';

    $potonganOtomatis = $detail->deductions()
        ->where('nama_potongan', $namaPotongan)
        ->where('keterangan', $penandaOtomatis)
        ->orderBy('id')
        ->get();

    if ($potongBpjs) {
        $potongan = $potonganOtomatis->first();

        if ($potongan) {
            $potongan->update(['nominal' => $tarifBpjs]);

            // Bersihkan duplikat otomatis jika sebelumnya ada.
            $potonganOtomatis->skip(1)->each(
                fn ($item) => $item->delete()
            );
        } else {
            $detail->deductions()->create([
                'nama_potongan' => $namaPotongan,
                'nominal' => $tarifBpjs,
                'keterangan' => $penandaOtomatis,
            ]);
        }
    } else {
        // Hanya hapus potongan otomatis BPJS periode ini.
        $potonganOtomatis->each(
            fn ($item) => $item->delete()
        );
    }

    $this->syncTotalPotongan($detail);

    return redirect()
        ->route('salary.index', $this->filterParams($request))
        ->with(
            'success',
            $potongBpjs
                ? 'Potongan BPJS berhasil diterapkan.'
                : 'Potongan BPJS berhasil dilepas.'
        );
}

}