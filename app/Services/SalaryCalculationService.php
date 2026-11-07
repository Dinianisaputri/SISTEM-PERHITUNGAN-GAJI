<?php

namespace App\Services;

use Carbon\Carbon;

use App\Models\EmployeeWorkSchedule;

use App\Models\DailyWorkSchedule;

use App\Models\Employee;

use App\Models\SalarySetting;

class SalaryCalculationService

{
    public function applyLateArrivalTolerance(
        Carbon $actualClockIn,
        ?string $scheduledClockIn
    ): Carbon {
        if (!$scheduledClockIn) {
            return $actualClockIn;
        }

        $scheduled = Carbon::parse(
            $actualClockIn->toDateString() . ' ' . Carbon::parse($scheduledClockIn)->format('H:i:s'),
            $actualClockIn->timezone
        );
        $lateMinutes = $scheduled->diffInMinutes($actualClockIn, false);

        if ($lateMinutes < -720) {
            $scheduled->subDay();
            $lateMinutes = $scheduled->diffInMinutes($actualClockIn, false);
        }

        return $lateMinutes >= 0 && $lateMinutes <= 10
            ? $scheduled
            : $actualClockIn;
    }


    /**
     * Ambil tarif terkini dari pengaturan database.
     * Nilai default digunakan jika pengaturan belum tersedia.
     */
    private function tarif(string $key, float $default): float
    {
        return SalarySetting::value($key, $default);
    }

    public function roundMinutesToWholeHours(int $minutes): int
    {
        $minutes = max($minutes, 0);
        $wholeHours = intdiv($minutes, 60);

        return $wholeHours + ($minutes % 60 >= 45 ? 1 : 0);
    }

    public function roundHoursToWhole(float $hours): int
    {
        return $this->roundMinutesToWholeHours(
            (int) floor(max($hours, 0) * 60)
        );
    }

    /*

    |--------------------------------------------------------------------------

    | BORONGAN

    |--------------------------------------------------------------------------

    |

    | Aturan:

    | - Gaji = jam kerja × tarif per jam

    | - Jam kerja sudah dikurangi istirahat

    | - Jam dibulatkan ke atas jika sisa menit mencapai 45 menit.
    | - Uang makan diberikan mulai 11 jam kerja bersih.

    | - Borongan tidak menggunakan lembur biasa/libur
    - untuk karyawan yang mempunyai data tarif BPJS maka dikurangi tarif

    |

    */

    public function calculateBorongan(

        ?string $jamMasuk,

        ?string $jamPulang,

        float $jamIstirahat = 1,

        float $tarifPerJam = 0

    ): array {

        if (!$jamMasuk || !$jamPulang) {

            return [

                'jam_bruto' => 0,

                'jam_istirahat' => round($jamIstirahat, 2),

                'jam_kerja' => 0,

                'gaji_pokok' => 0,

                'jam_lembur' => 0,

                'jam_lembur_biasa' => 0,

                'jam_lembur_libur' => 0,

                'total_lembur' => 0,

                'uang_makan_lembur' => 0,

                'gaji_kotor' => 0,

            ];

        }

        $masuk = Carbon::parse($jamMasuk);

        $pulang = Carbon::parse($jamPulang);

        /*

         * Jika jam pulang lebih kecil atau sama dengan jam masuk,

         * berarti pulang pada hari berikutnya.

         */

        if ($pulang->lessThanOrEqualTo($masuk)) {

            $pulang->addDay();

        }

        /*

         * Durasi bruto sebelum dikurangi istirahat.

         */

        $menitBruto = (int) $masuk->diffInMinutes($pulang);
        $jamBruto = $this->roundMinutesToWholeHours($menitBruto);

        /*

         * Jam kerja bersih setelah dikurangi istirahat.

         */

        $menitIstirahat = (int) round($jamIstirahat * 60);
        $jamKerja = $this->roundMinutesToWholeHours(
            max($menitBruto - $menitIstirahat, 0)
        );

        /*

         * Gaji pokok borongan.

         */

        $gajiPokok = $jamKerja * $tarifPerJam;

        /*

         * Uang makan borongan.

         *

         * Jam kerja sudah dibulatkan dengan aturan 45 menit.
         * Uang makan mulai dibayarkan saat jam kerja bersih mencapai 11 jam.

         */

        $uangMakan = $jamKerja >= 11

            ? $this->tarif('uang_makan_per_3_jam', 11000)

            : 0;

        $gajiKotor = $gajiPokok + $uangMakan;

        return [

            'jam_bruto' => round(

                $jamBruto,

                2

            ),

            'jam_istirahat' => round(

                $jamIstirahat,

                2

            ),

            'jam_kerja' => round(
                $jamKerja,
                0
            ),

            'gaji_pokok' => round(

                $gajiPokok,

                2

            ),

            /*

             * Borongan tidak menggunakan lembur per jam.

             */

            'jam_lembur' => 0,

            'jam_lembur_biasa' => 0,

            'jam_lembur_libur' => 0,

            'total_lembur' => 0,

            'uang_makan_lembur' => round(

                $uangMakan,

                2

            ),

            'gaji_kotor' => round(

                $gajiKotor,

                2

            ),

        ];

    }

    /*

    |--------------------------------------------------------------------------

    | HARIAN / BULANAN

    |--------------------------------------------------------------------------

    |

    | HARI BIASA:

    |

    | - Jam kerja sudah dikurangi istirahat.

    | - 8 jam pertama = jam normal.

    | - Kelebihan dari 8 jam = lembur.

    | - Sisa lembur < 45 menit = tidak dihitung.

    | - Sisa lembur >= 45 menit = dibulatkan menjadi 1 jam.

    | - Lembur biasa = Rp18.500/jam.

    |

    | Contoh:

    |

    | 8 jam 00 menit -> 0 jam lembur

    | 8 jam 30 menit -> 0 jam lembur

    | 8 jam 44 menit -> 0 jam lembur

    | 8 jam 45 menit -> 1 jam lembur

    | 9 jam 00 menit -> 1 jam lembur

    | 9 jam 30 menit -> 1 jam lembur

    | 9 jam 45 menit -> 2 jam lembur

    |

    | HARI LIBUR:

    |

    | - Seluruh jam kerja bersih dihitung sebagai lembur.

    | - Tidak ada 8 jam normal.

    | - Lembur libur = Rp37.000/jam.

    |

    | DAPUR:

    |

    | - Tidak mendapatkan lembur.

    |

    */

    public function calculateHarianBulanan(

        float $tarifHarian,

        float $jamKerja,

        bool $isHoliday = false,

        bool $isDapur = false,

        ?object $schedule = null,

        ?string $tanggal = null,

        ?string $jamPulangAktual = null

    ): array {

        /*

         * Parameter $schedule, $tanggal, dan $jamPulangAktual

         * tetap dipertahankan agar tidak merusak pemanggilan

         * dari SalaryController.

         *

         * Parameter jadwal dan jam pulang aktual digunakan untuk

         * menghitung lembur berdasarkan jam pulang jadwal.

         */

        /*

        |--------------------------------------------------------------------------

        | DAPUR

        |--------------------------------------------------------------------------

        |

        | Dapur tidak mendapatkan lembur.

        |

        */

        if ($isDapur) {

            $jamNormal = min(
                $this->roundHoursToWhole($jamKerja),
                8
            );

            $gajiPokok = $tarifHarian;

            return [

                'jam_normal' => round(

                    $jamNormal,

                    2

                ),

                'jam_kerja' => round(

                    $jamNormal,

                    2

                ),

                'jam_lembur' => 0,

                'jam_lembur_biasa' => 0,

                'jam_lembur_libur' => 0,

                'gaji_pokok' => round(

                    $gajiPokok,

                    2

                ),

                'total_lembur' => 0,

                'uang_makan_lembur' => 0,

                'gaji_kotor' => round(

                    $gajiPokok,

                    2

                ),

            ];

        }

        /*

        |--------------------------------------------------------------------------

        | HARI LIBUR

        |--------------------------------------------------------------------------

        |

        | Pada hari libur, seluruh jam kerja bersih

        | dihitung sebagai lembur hari libur.

        |

        */

        if ($isHoliday) {

            $jamLembur = $this->roundHoursToWhole($jamKerja);

            $jamLemburBiasa = 0;

            $jamLemburLibur = $jamLembur;

            /*

             * Tarif lembur hari libur.

             */

            $totalLembur =

                $jamLemburLibur

                * $this->tarif('lembur_libur_per_jam', 37000);

            /*

             * Uang makan:

             *

             * Uang makan diberikan satu kali jika lembur hari libur mencapai 3 jam.

             *

             * Contoh:
             * Lembur >= 3 jam -> 1

             */

            $uangMakan = $jamLemburLibur >= 3
                ? $this->tarif('uang_makan_per_3_jam', 11000)
                : 0;

            return [

                /*

                 * Hari libur tidak memiliki jam normal.

                 */

                'jam_normal' => 0,

                /*

                 * Tetap 0 karena seluruh jam kerja

                 * masuk sebagai lembur.

                 */

                'jam_kerja' => 0,

                'jam_lembur' => round(

                    $jamLembur,

                    2

                ),

                'jam_lembur_biasa' => 0,

                'jam_lembur_libur' => round(

                    $jamLemburLibur,

                    2

                ),

                'gaji_pokok' => 0,

                'total_lembur' => round(

                    $totalLembur,

                    2

                ),

                'uang_makan_lembur' => round(

                    $uangMakan,

                    2

                ),

                'gaji_kotor' => round(

                    $totalLembur + $uangMakan,

                    2

                ),

            ];

        }

        /*

        |--------------------------------------------------------------------------

        | HARI BIASA

        |--------------------------------------------------------------------------

        |

        | Dasar perhitungan:

        |

        | $jamKerja sudah merupakan jam kerja bersih

        | setelah dikurangi jam istirahat.

        |

        | 8 jam = normal.

        | Lebih dari 8 jam = lembur.

        |

        */

        $jamNormal = min(
            $this->roundHoursToWhole($jamKerja),
            8
        );

        /*

         * Hitung kelebihan jam kerja dari 8 jam normal.

         */

        /*
         * Lembur dihitung dari jam pulang aktual dibandingkan
         * jam pulang jadwal. Jam pulang dini hari dapat menjadi
         * checkout hari berikutnya jika selisihnya lebih dari 12 jam.
         * Sisa 45 menit atau lebih dibulatkan menjadi 1 jam lembur.
         */
        $menitLembur = 0;

        if ($schedule && !empty($schedule->jam_pulang) && $jamPulangAktual && $tanggal) {
            $jadwalPulang = Carbon::parse($tanggal . ' ' . $schedule->jam_pulang);
            $aktualPulang = Carbon::parse($tanggal . ' ' . $jamPulangAktual);

            /*
             * Jika jam pulang aktual jauh lebih awal daripada jam pulang
             * jadwal (lebih dari 12 jam), anggap pulang terjadi keesokan hari.
             * Contoh: jadwal 15:00, aktual 00:48 -> 00:48 hari berikutnya.
             * Selisih kecil seperti jadwal 16:00 dan aktual 15:59 tetap
             * dianggap pulang lebih awal, bukan shift lintas tengah malam.
             */
            $selisihMenitMentah = $jadwalPulang->diffInMinutes($aktualPulang, false);

            if ($selisihMenitMentah < -720) {
                $aktualPulang->addDay();
            }

            if ($aktualPulang->lessThanOrEqualTo($jadwalPulang)) {
                $menitLembur = 0;
            } else {
                $menitLembur = (int) $jadwalPulang->diffInMinutes($aktualPulang);
            }
        } else {
            // Fallback jika jadwal pulang tidak tersedia.
            $menitLembur = (int) round(max($jamKerja - 8, 0) * 60);
        }

        $jamLembur = $this->roundMinutesToWholeHours($menitLembur);

        $jamLemburBiasa = $jamLembur;

        $jamLemburLibur = 0;

        /*

         * Total uang lembur biasa.

         */

        $totalLembur =

            $jamLemburBiasa

            * $this->tarif('lembur_biasa_per_jam', 18500);

        /*

        |--------------------------------------------------------------------------

        | UANG MAKAN LEMBUR

        |--------------------------------------------------------------------------

        |

        | Uang makan diberikan satu kali jika lembur mencapai 3 jam.

        |

        */

        $uangMakan = $jamLemburBiasa >= 3
            ? $this->tarif('uang_makan_per_3_jam', 11000)
            : 0;

        /*

        |--------------------------------------------------------------------------

        | GAJI POKOK & GAJI KOTOR

        |--------------------------------------------------------------------------

        */

        $gajiPokok = $tarifHarian;

        $gajiKotor =

            $gajiPokok

            + $totalLembur

            + $uangMakan;

        /*

        |--------------------------------------------------------------------------

        | HASIL

        |--------------------------------------------------------------------------

        */

        return [

            'jam_normal' => round(

                $jamNormal,

                2

            ),

            'jam_kerja' => round(

                $jamNormal,

                2

            ),

            'jam_lembur' => round(

                $jamLembur,

                2

            ),

            'jam_lembur_biasa' => round(
                $jamLemburBiasa,
                0
            ),

            'jam_lembur_libur' => 0,

            'gaji_pokok' => round(

                $gajiPokok,

                2

            ),

            'total_lembur' => round(

                $totalLembur,

                2

            ),

            'uang_makan_lembur' => round(

                $uangMakan,

                2

            ),

            'gaji_kotor' => round(

                $gajiKotor,

                2

            ),

        ];

    }
    /*

    |--------------------------------------------------------------------------

    | POTONGAN

    |--------------------------------------------------------------------------

    */

    public function calculateGajiBersih(

        float $gajiKotor,

        float $totalPotongan = 0

    ): array {

        $gajiBersih = max(

            $gajiKotor - $totalPotongan,

            0

        );

        return [

            'total_potongan' => round(

                $totalPotongan,

                2

            ),

            'gaji_bersih' => round(

                $gajiBersih,

                2

            ),

        ];

    }

    /*

    |--------------------------------------------------------------------------

    | TOTAL PERIODE

    |--------------------------------------------------------------------------

    */

    public function calculateTotalPeriode(

        array $hasilHarian,

        float $totalPotongan = 0

    ): array {

        $hariKerja = 0;

        $jamKerja = 0;

        $jamLemburBiasa = 0;

        $jamLemburLibur = 0;

        $gajiPokok = 0;

        $totalLembur = 0;

        $uangMakan = 0;
        $uangMakanPerTanggal = [];

        foreach ($hasilHarian as $hasil) {

            /*

             * Hanya data yang mempunyai jam kerja

             * yang dihitung sebagai hari kerja.

             */

            if (

                ($hasil['jam_kerja'] ?? 0) > 0

            ) {

                $hariKerja++;

            }

            $jamKerja +=

                $hasil['jam_kerja'] ?? 0;

            $jamLemburBiasa +=

                $hasil['jam_lembur_biasa'] ?? 0;

            $jamLemburLibur +=

                $hasil['jam_lembur_libur'] ?? 0;

            $gajiPokok +=

                $hasil['gaji_pokok'] ?? 0;

            $totalLembur +=

                $hasil['total_lembur'] ?? 0;

            $nominalUangMakan = (float) ($hasil['uang_makan_lembur'] ?? 0);
            $tanggal = $hasil['tanggal'] ?? null;

            if ($tanggal === null || $tanggal === '') {
                $uangMakan += $nominalUangMakan;
            } else {
                $tanggal = is_object($tanggal) && method_exists($tanggal, 'format')
                    ? $tanggal->format('Y-m-d')
                    : substr((string) $tanggal, 0, 10);
                $uangMakanPerTanggal[$tanggal] = max(
                    $uangMakanPerTanggal[$tanggal] ?? 0,
                    $nominalUangMakan
                );
            }
        }

        $uangMakan += array_sum($uangMakanPerTanggal);

        /*

         * Total gaji kotor.

         */

        $gajiKotor =

            $gajiPokok

            + $totalLembur

            + $uangMakan;

        /*

         * Total gaji bersih.

         */

        $gajiBersih = max(

            $gajiKotor - $totalPotongan,

            0

        );

        return [

            'hari_kerja' => $hariKerja,

            'jam_kerja' => round(

                $jamKerja,

                2

            ),

            'jam_lembur_biasa' => round(

                $jamLemburBiasa,

                2

            ),

            'jam_lembur_libur' => round(
                $jamLemburLibur,
                0
            ),

            'gaji_pokok' => round(

                $gajiPokok,

                2

            ),

            'total_lembur' => round(

                $totalLembur,

                2

            ),

            'uang_makan_lembur' => round(

                $uangMakan,

                2

            ),

            'total_potongan' => round(

                $totalPotongan,

                2

            ),

            'gaji_kotor' => round(

                $gajiKotor,

                2

            ),

            'gaji_bersih' => round(

                $gajiBersih,

                2

            ),

        ];

    }

    /*

    |--------------------------------------------------------------------------

    | GET SCHEDULE

    |--------------------------------------------------------------------------

    |

    | Method ini tetap dipertahankan karena mungkin masih digunakan

    | oleh bagian lain dari sistem.

    |

    */

    private function getScheduleForDate(

        int $employeeId,

        string $tanggal

    ): ?object {

        $employee = Employee::find($employeeId);

        if (!$employee) {

            return null;

        }

        // Prioritas 1: jadwal khusus karyawan

        $jadwalKhusus = EmployeeWorkSchedule::with('workSchedule')

            ->where('employee_id', $employeeId)

            ->whereDate('tanggal', $tanggal)

            ->first();

        if ($jadwalKhusus?->workSchedule) {

            return $jadwalKhusus->workSchedule;

        }

        // Prioritas 2: jadwal berdasarkan tanggal dan penempatan

        if (empty($employee->penempatan)) {

            return null;

        }

        return DailyWorkSchedule::with('workSchedule')

            ->whereDate('tanggal', $tanggal)

            ->where('penempatan', $employee->penempatan)

            ->first()?->workSchedule;

    }

}
