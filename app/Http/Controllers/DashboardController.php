<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $param['pemasukan'] =  Pemasukan::sum('nominal');
        $param['pengeluaran'] =  Pengeluaran::sum('nominal');
        $pemasukan = Pemasukan::orderBy('date','ASC')->select('nominal','date')->get()->toArray();
        $pengeluaran = Pengeluaran::orderBy('date','ASC')->select('nominal','date')->get()->toArray();
        $nominals = array_map(function($entry) {
            return $entry['nominal'];
        }, $pemasukan);
        $param['nominal_pemasukan'] = $nominals;
        $nominals = array_map(function($entry) {
            return $entry['nominal'];
        }, $pengeluaran);
        $param['nominal_pengeluaran'] = $nominals;
        $param['bulan'] = Pemasukan::select('date')
                ->orderBy('date', 'ASC')
                ->get()
                ->map(function($item) {
                    return Carbon::parse($item->date)->format('F Y'); // Contoh: 'January 2021'
                })
                ->toArray();
        return view('dashboard',$param);
    }
}
