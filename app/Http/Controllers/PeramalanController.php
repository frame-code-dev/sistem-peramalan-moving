<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Dcvn\Math\Statistics\MovingAverage;
use Illuminate\Http\Request;

class PeramalanController extends Controller
{
    public function index(){
        $param['title'] = "Peramalan";
        $pemasukan = Pemasukan::all()->pluck('nominal')->toArray();
        $pengeluaran = Pengeluaran::all()->pluck('nominal')->toArray();
        // Periode moving average yang diinginkan
        $periode = 3;
        $movingAverage = new MovingAverage(MovingAverage::WEIGHTED_ARITHMETIC);
        $movingAverage->setPeriod($periode);

        $param['moving_pemasukan'] = $movingAverage->getCalculatedFromArray($pemasukan);
        $param['moving_pengeluaran'] = $movingAverage->getCalculatedFromArray($pengeluaran);
        $param['bulan'] = array_map(function($i) {
            $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            return $months[($i - 1) % 12];
        }, range(1, count($pemasukan)));
        return view('backoffice.peramalan.index', $param);
    }

    function hitungMovingAverage($data, $periode) {
        $movingAverages = [];
        $jumlahData = count($data);

        for ($i = 0; $i <= $jumlahData - $periode; $i++) {
            $total = 0;
            for ($j = 0; $j < $periode; $j++) {
                $total += $data[$i + $j];
            }
            $movingAverages[] = $total / $periode;
        }

        return $movingAverages;
    }
}
