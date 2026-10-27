<?php

namespace App\Http\Controllers;

use App\Imports\EmployeeWorkScheduleImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeWorkScheduleImportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:10240',
            ],
        ]);

        Excel::import(
            new EmployeeWorkScheduleImport,
            $request->file('file')
        );

        return back()->with(
            'success',
            'Data jadwal kerja berhasil diimport.'
        );
    }
}