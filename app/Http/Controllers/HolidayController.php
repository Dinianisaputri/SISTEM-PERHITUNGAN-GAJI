<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::orderBy('tanggal')->get();

        return view('holidays.index', compact('holidays'));
    }

    public function create()
    {
        return view('holidays.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date', 'unique:holidays,tanggal'],
            'nama_libur' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'is_national' => ['nullable', 'boolean'],
        ]);

        $validated['is_national'] = $request->boolean('is_national');

        Holiday::create($validated);

        return redirect()
            ->route('holidays.index')
            ->with('success', 'Hari libur berhasil ditambahkan.');
    }

    public function edit(Holiday $holiday)
    {
        return view('holidays.edit', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $validated = $request->validate([
            'tanggal' => [
                'required',
                'date',
                'unique:holidays,tanggal,' . $holiday->id,
            ],
            'nama_libur' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'is_national' => ['nullable', 'boolean'],
        ]);

        $validated['is_national'] = $request->boolean('is_national');

        $holiday->update($validated);

        return redirect()
            ->route('holidays.index')
            ->with('success', 'Hari libur berhasil diperbarui.');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return redirect()
            ->route('holidays.index')
            ->with('success', 'Hari libur berhasil dihapus.');
    }
}