<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Dcvn\Math\Statistics\MovingAverage;
use Illuminate\Http\Request;

class PeramalanController extends Controller
{
    public function index(){
        $param['title'] = "Peramalan";

        // START PEMASUKAN
        $pemasukan = Pemasukan::orderBy('date','ASC')->select('nominal','date')->get()->toArray();
        $nominals = array_map(function($entry) {
            return $entry['nominal'];
        }, $pemasukan);
        $param['pemasukan'] = $nominals;
        $window = 3;
        $start_index = 2; // Indeks dari mana prediksi dimulai
        $calculated_moving_average = $this->moving_average($nominals, $window);
        $moving_pemasukan = array_fill(0, $start_index, null); // Mengisi nilai null hingga start_index
        $param['moving_pemasukan'] = array_merge($moving_pemasukan, $calculated_moving_average); // Menggabungkan dengan nilai Moving Average yang dihitung

        $sum_squared_error = 0;
        $sum_absolute_error = 0;
        $sum_absolute_percentage_error = 0;
        $prediksi = array_merge(array_fill(0, $window - 1, 0), $calculated_moving_average);
        $data = [];
        foreach ($pemasukan as $index => &$entry) {
            $entry['tahun'] = Carbon::parse($entry['date'])->format('F Y');
            if ($index < $window - 1) {
                $entry['Fx'] = 0;
                $entry['e'] = 0;
                $entry['e2'] = 0;
                $entry['|e|'] = 0;
                $entry['|e|/Y'] = 0;
            } else {
                $entry['Fx'] = $prediksi[$index];
                $entry['e'] = $entry['nominal'] - $entry['Fx'];
                $entry['e2'] = $entry['e'] ** 2;
                $entry['|e|'] = abs($entry['e']);
                $entry['|e|/Y'] = $entry['|e|'] / $entry['nominal'];

                $sum_squared_error += $entry['e2'];
                $sum_absolute_error += $entry['|e|'];
                $sum_absolute_percentage_error += $entry['|e|/Y'];
            }
            $data[] = $entry;
        }
        $param['table_pemasukan'] = $data;
        $param['count'] = count($pemasukan) - ($window - 1);
        $param['mse'] = $sum_squared_error / $param['count'];
        $param['rmse'] = sqrt($param['mse']);
        $param['mae'] = $sum_absolute_error / $param['count'];
        $param['mape'] = ($sum_absolute_percentage_error / $param['count']) * 100;

        $param['bulan'] = Pemasukan::select('date')
        ->orderBy('date', 'ASC')
        ->get()
        ->map(function($item) {
            return Carbon::parse($item->date)->format('F Y'); // Contoh: 'January 2021'
        })
        ->toArray();
        // END PEMASUKAN
        // START PENGELUARAN
        $pengeluaran = Pengeluaran::orderBy('date','ASC')->select('nominal','date')->get()->toArray();
        $nominals = array_map(function($entry) {
            return $entry['nominal'];
        }, $pengeluaran);
        $param['pengeluaran'] = $nominals;
        $calculated_moving_average_pengeluaran = $this->moving_average($nominals, $window);
        $moving_pengeluaran = array_fill(0, $start_index, null); // Mengisi nilai null hingga start_index
        $param['moving_pengeluaran'] = array_merge($moving_pengeluaran, $calculated_moving_average_pengeluaran); // Menggabungkan dengan nilai Moving Average yang dihitung

        $sum_squared_error_pengeluaran = 0;
        $sum_absolute_error_pengeluaran = 0;
        $sum_absolute_percentage_error_pengeluaran = 0;
        $prediksi_pengeluaran = array_merge(array_fill(0, $window - 1, 0), $calculated_moving_average_pengeluaran);
        $data_pengeluaran = [];
        foreach ($pengeluaran as $index => &$entry) {
            $entry['tahun'] = Carbon::parse($entry['date'])->format('F Y');
            if ($index < $window - 1) {
                $entry['Fx'] = 0;
                $entry['e'] = 0;
                $entry['e2'] = 0;
                $entry['|e|'] = 0;
                $entry['|e|/Y'] = 0;
            } else {
                $entry['Fx'] = $prediksi_pengeluaran[$index];
                $entry['e'] = $entry['nominal'] - $entry['Fx'];
                $entry['e2'] = $entry['e'] ** 2;
                $entry['|e|'] = abs($entry['e']);
                $entry['|e|/Y'] = $entry['|e|'] / $entry['nominal'];

                $sum_squared_error_pengeluaran += $entry['e2'];
                $sum_absolute_error_pengeluaran += $entry['|e|'];
                $sum_absolute_percentage_error_pengeluaran += $entry['|e|/Y'];
            }
            $data_pengeluaran[] = $entry;
        }
        $param['table_pengeluaran'] = $data_pengeluaran;
        $param['count_pengeluaran'] = count($pengeluaran) - ($window - 1);
        $param['mse_pengeluaran'] = $sum_squared_error_pengeluaran / $param['count_pengeluaran'];
        $param['rmse_pengeluaran'] = sqrt($param['mse_pengeluaran']);
        $param['mae_pengeluaran'] = $sum_absolute_error_pengeluaran / $param['count_pengeluaran'];
        $param['mape_pengeluaran'] = ($sum_absolute_percentage_error_pengeluaran / $param['count_pengeluaran']) * 100;

        $param['bulan_pengeluaran'] = Pengeluaran::select('date')
                                    ->orderBy('date', 'ASC')
                                    ->get()
                                    ->map(function($item) {
                                        return Carbon::parse($item->date)->format('F Y'); // Contoh: 'January 2021'
                                    })
                                    ->toArray();


        // Memastikan kedua array memiliki panjang yang sama
        $length = min(count($param['moving_pemasukan']), count($param['moving_pengeluaran']));
        $moving_pemasukan = array_slice($param['moving_pemasukan'], 0, $length);
        $moving_pengeluaran = array_slice($param['moving_pengeluaran'], 0, $length);
        // Menghitung total pendapatan
        $total_pendapatan = array_map(function($pemasukan, $pengeluaran) {
            return $pemasukan - $pengeluaran;
        }, $moving_pemasukan, $moving_pengeluaran);
        $param['total_pendapatan'] = end($total_pendapatan);
        return view('backoffice.peramalan.index', $param);
    }

    function moving_average($data, $window) {
        $result = [];
        $dataCount = count($data);

        for ($i = 0; $i <= $dataCount - $window; $i++) {
            $windowData = array_slice($data, $i, $window);
            $average = array_sum($windowData) / $window;
            $result[] = round($average, 2);
        }

        return $result;
    }

    function mean_absolute_percentage_error($actual, $forecast) {
        $actualCount = count($actual);
        $mape = 0.0;

        for ($i = 0; $i < $actualCount; $i++) {
            if ($actual[$i] != 0) {
                $mape += abs(($actual[$i] - $forecast[$i]) / $actual[$i]);
            }
        }

        return ($mape / $actualCount) * 100;
    }


}
