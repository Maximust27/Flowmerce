<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class EmployeeExportController extends Controller
{
    public function export()
    {
        $employees = User::latest()->get();
        $title = 'Laporan Daftar Pegawai / Karyawan';

        $pdf = Pdf::loadView('pegawai.pdf', compact('employees', 'title'));
        return $pdf->download('laporan-pegawai-'.date('Ymd').'.pdf');
    }
}
