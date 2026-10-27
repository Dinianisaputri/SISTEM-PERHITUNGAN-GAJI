<?php
namespace App\Http\Controllers;

use App\Models\DailyWorkSchedule;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DailyWorkScheduleController extends Controller
{
    /**
     * Daftar penempatan aktif dari Master Data.
     */
    private function penempatanList(): array
    {
        return DB::table('master_penempatans')
            ->where('is_active', true)
            ->orderBy('nama')
            ->pluck('nama')
            ->toArray();
    }

    /**
     * Daftar jadwal kerja harian.
     */
    public function index(Request $request)
    {
        $query = DailyWorkSchedule::with('workSchedule')
            ->orderByDesc('tanggal')
            ->orderBy('penempatan');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('penempatan')) {
            $query->where('penempatan', $request->penempatan);
        }

        $dailySchedules = $query->paginate(15)->withQueryString();

        return view('daily-work-schedules.index', [
            'dailySchedules' => $dailySchedules,
            'penempatanList' => $this->penempatanList(),
        ]);
    }

    /**
     * Form tambah jadwal.
     */
    public function create()
    {
        return view('daily-work-schedules.create', [
            'dailySchedule' => null,
            'workSchedules' => WorkSchedule::where('is_active', true)
                ->orderBy('kode')
                ->get(),
            'penempatanList' => $this->penempatanList(),
        ]);
    }

    /**
     * Simpan jadwal baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'penempatan' => [
                'required',
                'string',
                'max:255',
                Rule::in($this->penempatanList()),
            ],
            'work_schedule_id' => [
                'required',
                'integer',
                Rule::exists('work_schedules', 'id')
                    ->where('is_active', true),
            ],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $exists = DailyWorkSchedule::whereDate(
            'tanggal',
            $validated['tanggal']
        )
            ->where('penempatan', $validated['penempatan'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'penempatan' =>
                        'Jadwal untuk penempatan ini pada tanggal tersebut sudah ada. Gunakan Edit untuk mengubahnya.',
                ]);
        }

        DailyWorkSchedule::create($validated);

        return redirect()
            ->route('daily-work-schedules.index')
            ->with('success', 'Jadwal kerja harian berhasil disimpan.');
    }

    /**
     * Form edit jadwal.
     */
    public function edit(DailyWorkSchedule $dailyWorkSchedule)
    {
        $penempatanList = $this->penempatanList();

        // Pertahankan penempatan lama jika sudah dinonaktifkan.
        if (
            $dailyWorkSchedule->penempatan &&
            !in_array(
                $dailyWorkSchedule->penempatan,
                $penempatanList,
                true
            )
        ) {
            $penempatanList[] = $dailyWorkSchedule->penempatan;
            sort($penempatanList);
        }

        return view('daily-work-schedules.edit', [
            'dailySchedule' => $dailyWorkSchedule,
            'workSchedules' => WorkSchedule::where('is_active', true)
                ->orWhere('id', $dailyWorkSchedule->work_schedule_id)
                ->orderBy('kode')
                ->get(),
            'penempatanList' => $penempatanList,
        ]);
    }

    /**
     * Perbarui jadwal.
     */
    public function update(
        Request $request,
        DailyWorkSchedule $dailyWorkSchedule
    ) {
        $penempatanDiizinkan = $this->penempatanList();

        // Izinkan nilai lama agar jadwal terdahulu tetap bisa diedit.
        if (
            $dailyWorkSchedule->penempatan &&
            !in_array(
                $dailyWorkSchedule->penempatan,
                $penempatanDiizinkan,
                true
            )
        ) {
            $penempatanDiizinkan[] = $dailyWorkSchedule->penempatan;
        }

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'penempatan' => [
                'required',
                'string',
                'max:255',
                Rule::in(array_values(array_unique($penempatanDiizinkan))),
            ],
            'work_schedule_id' => [
                'required',
                'integer',
                Rule::exists('work_schedules', 'id'),
            ],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $duplicate = DailyWorkSchedule::whereDate(
            'tanggal',
            $validated['tanggal']
        )
            ->where('penempatan', $validated['penempatan'])
            ->where('id', '!=', $dailyWorkSchedule->id)
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'penempatan' =>
                        'Penempatan ini sudah memiliki jadwal pada tanggal tersebut.',
                ]);
        }

        $dailyWorkSchedule->update($validated);

        return redirect()
            ->route('daily-work-schedules.index')
            ->with('success', 'Jadwal kerja harian berhasil diperbarui.');
    }
}
