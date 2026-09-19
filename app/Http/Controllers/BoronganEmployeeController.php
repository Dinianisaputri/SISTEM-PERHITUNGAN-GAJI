<?php

namespace App\Http\Controllers;

use App\Models\BoronganEmployee;
use Illuminate\Http\Request;

class BoronganEmployeeController extends Controller
{
    public function index()
    {
        $employees = BoronganEmployee::orderBy('no_id')->get();

        return view('borongan_employees.index', compact('employees'));
    }

    public function create()
    {
        return view('borongan_employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_id' => 'required|unique:borongan_employees,no_id',
            'nik' => 'nullable',
            'name' => 'required',
            'bagian' => 'nullable',
        ], [
            'no_id.required' => 'No. ID wajib diisi.',
            'no_id.unique' => 'No. ID sudah terdaftar.',
            'name.required' => 'Nama karyawan wajib diisi.',
        ]);

        BoronganEmployee::create([
            'no_id' => $request->no_id,
            'nik' => $request->nik,
            'name' => $request->name,
            'bagian' => $request->bagian,
        ]);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function edit(BoronganEmployee $employee)
    {
        return view('borongan_employees.edit', compact('employee'));
    }

    public function update(Request $request, BoronganEmployee $employee)
    {
        $request->validate([
            'no_id' => 'required|unique:borongan_employees,no_id,' . $employee->id,
            'nik' => 'nullable',
            'name' => 'required',
            'bagian' => 'nullable',
        ], [
            'no_id.required' => 'No. ID wajib diisi.',
            'no_id.unique' => 'No. ID sudah digunakan.',
            'name.required' => 'Nama karyawan wajib diisi.',
        ]);

        $employee->update([
            'no_id' => $request->no_id,
            'nik' => $request->nik,
            'name' => $request->name,
            'bagian' => $request->bagian,
        ]);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(BoronganEmployee $employee)
    {
        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'Data karyawan berhasil dihapus.');
    }
}