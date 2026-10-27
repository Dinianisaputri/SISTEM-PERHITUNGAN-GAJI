<?php

namespace App\Http\Controllers;

use App\Models\WorkSchedule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkScheduleController extends Controller
{
    public function index()
    {
        $workSchedules = WorkSchedule::orderBy('kode')->paginate(15);

        return view('work-schedules.index', compact('workSchedules'));
    }

    public function create()
    {
        return view('work-schedules.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:255', 'unique:work_schedules,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'jam_masuk' => ['required', 'date_format:H:i'],
            'jam_pulang' => ['required', 'date_format:H:i'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        WorkSchedule::create($validated);

        return redirect()
            ->route('work-schedules.index')
            ->with('success', 'Master jam kerja berhasil ditambahkan.');
    }

    public function edit(WorkSchedule $workSchedule)
    {
        return view('work-schedules.edit', compact('workSchedule'));
    }

    public function update(Request $request, WorkSchedule $workSchedule)
    {
        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:255',
                Rule::unique('work_schedules', 'kode')->ignore($workSchedule->id),
            ],
            'nama' => ['required', 'string', 'max:255'],
            'jam_masuk' => ['required', 'date_format:H:i'],
            'jam_pulang' => ['required', 'date_format:H:i'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $workSchedule->update($validated);

        return redirect()
            ->route('work-schedules.index')
            ->with('success', 'Master jam kerja berhasil diperbarui.');
    }

    public function destroy(WorkSchedule $workSchedule)
    {
        // Jadwal tidak dihapus agar riwayat dan relasi lama tetap aman.
        $workSchedule->update([
            'is_active' => false,
        ]);

        return redirect()
            ->route('work-schedules.index')
            ->with('success', 'Jadwal berhasil dinonaktifkan.');
    }
}
