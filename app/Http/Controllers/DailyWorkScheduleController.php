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
     * Daftar bagian dari Master Data.
     */
    private function bagianList(): array
    {
        return DB::table('master_bagians')
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
            ->orderBy('bagian');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('bagian')) {
            $query->where('bagian', $request->bagian);
        }

        $dailySchedules = $query->paginate(15)->withQueryString();

        return view('daily-work-schedules.index', [
            'dailySchedules' => $dailySchedules,
            'bagianList' => $this->bagianList(),
        ]);
    }

    /**
     * Form tambah jadwal.
     */
    public function create()
    {
        return view('daily-work-schedules.create', [
            'dailySchedule' => null,
            'workSchedules' => WorkSchedule::orderBy('kode')->get(),
            'bagianList' => $this->bagianList(),
        ]);
    }

    /**
     * Simpan jadwal baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'bagian' => [
                'required',
                'string',
                'max:255',
                Rule::in($this->bagianList()),
            ],
            'work_schedule_id' => [
                'required',
                'integer',
                Rule::exists('work_schedules', 'id'),
            ],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $exists = DailyWorkSchedule::whereDate(
            'tanggal',
            $validated['tanggal']
        )
            ->where('bagian', $validated['bagian'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'bagian' =>
                        'Jadwal untuk bagian ini pada tanggal tersebut sudah ada. Gunakan Edit untuk mengubahnya.',
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
        $bagianList = $this->bagianList();

        // Pertahankan bagian lama meskipun data masternya sudah dihapus.
        if (
            $dailyWorkSchedule->bagian &&
            !in_array(
                $dailyWorkSchedule->bagian,
                $bagianList,
                true
            )
        ) {
            $bagianList[] = $dailyWorkSchedule->bagian;
            sort($bagianList);
        }

        return view('daily-work-schedules.edit', [
            'dailySchedule' => $dailyWorkSchedule,
            'workSchedules' => WorkSchedule::orderBy('kode')->get(),
            'bagianList' => $bagianList,
        ]);
    }

    /**
     * Perbarui jadwal.
     */
    public function update(
        Request $request,
        DailyWorkSchedule $dailyWorkSchedule
    ) {
        $bagianDiizinkan = $this->bagianList();

        // Izinkan bagian lama agar jadwal terdahulu tetap bisa diedit.
        if (
            $dailyWorkSchedule->bagian &&
            !in_array(
                $dailyWorkSchedule->bagian,
                $bagianDiizinkan,
                true
            )
        ) {
            $bagianDiizinkan[] = $dailyWorkSchedule->bagian;
        }

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'bagian' => [
                'required',
                'string',
                'max:255',
                Rule::in(array_values(array_unique($bagianDiizinkan))),
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
            ->where('bagian', $validated['bagian'])
            ->where('id', '!=', $dailyWorkSchedule->id)
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'bagian' =>
                        'Bagian ini sudah memiliki jadwal pada tanggal tersebut.',
                ]);
        }

        $dailyWorkSchedule->update($validated);

        return redirect()
            ->route('daily-work-schedules.index')
            ->with('success', 'Jadwal kerja harian berhasil diperbarui.');
    }
}
