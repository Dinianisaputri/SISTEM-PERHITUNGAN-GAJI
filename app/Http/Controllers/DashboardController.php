<?php

namespace App\Http\Controllers;

use App\Models\BoronganEmployee;
use App\Models\Checklog;
use App\Models\SalaryArchive;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKaryawan = BoronganEmployee::count();

        $totalChecklog = Checklog::count();

        $totalArsip = SalaryArchive::count();

        $totalGaji = SalaryArchive::sum('total_salary');

        return view('dashboard', compact(
            'totalKaryawan',
            'totalChecklog',
            'totalArsip',
            'totalGaji'
        ));
    }
}