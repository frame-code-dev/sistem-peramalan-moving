<x-app-layout>
    @push('js')
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
    <script>
        // var ctx = document.getElementById('myChart').getContext('2d');
        // var chart = new Chart(ctx, {
        //     type: 'line',
        //     data: {
        //         labels: @json($bulan),
        //         datasets: [{
        //             label: 'Moving Average Pemasukan Actual',
        //             data: @json($pemasukan),
        //             borderColor: '#000',
        //             borderWidth: 1,
        //             fill: false
        //         },
        //         {
        //             label: 'Moving Average Pemasukan',
        //             data: @json($moving_pemasukan),
        //             borderColor: 'rgba(75, 192, 192, 1)',
        //             borderWidth: 1,
        //             fill: false
        //         },
        //     ]
        //     },
        //     options: {
        //         scales: {
        //             y: {
        //                 beginAtZero: true
        //             }
        //         }
        //     }
        // });
        // Pemasukan
        var options = {
            series: [
            {
                name: "Aktual",
                data: @json($pemasukan),
            },
            {
                name: "Prediksi",
                data: @json($moving_pemasukan),
            }
            ],
            chart: {
            height: 350,
            type: 'line',
            dropShadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 0.2
            },
            zoom: {
                enabled: false
            },
            toolbar: {
                show: false
            }
            },
            colors: ['#77B6EA', '#545454'],
            dataLabels: {
            enabled: true,
            },
            stroke: {
            curve: 'straight'
            },
            title: {
            text: 'Moving Average Pemasukan',
            align: 'left'
            },
            grid: {
            borderColor: '#e7e7e7',
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
            },
            markers: {
            size: 1
            },
            xaxis: {
            categories: @json($bulan),
            title: {
                text: 'Month'
            }
            },
            yaxis: {
            title: {
                text: 'Moving Average'
            },

            },
            legend: {
            position: 'top',
            horizontalAlign: 'right',
            floating: true,
            offsetY: -25,
            offsetX: -5
            }
        };
        var chart = new ApexCharts(document.querySelector("#myChart"), options);
        chart.render();
        // Pengeluaran
        var options_pengeluaran = {
            series: [
            {
                name: "Aktual",
                data: @json($pengeluaran),
            },
            {
                name: "Prediksi",
                data: @json($moving_pengeluaran),
            }
            ],
            chart: {
            height: 350,
            type: 'line',
            dropShadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 0.2
            },
            zoom: {
                enabled: false
            },
            toolbar: {
                show: false
            }
            },
            colors: ['#77B6EA', '#545454'],
            dataLabels: {
            enabled: true,
            },
            stroke: {
            curve: 'straight'
            },
            title: {
            text: 'Moving Average Pengeluaran',
            align: 'left'
            },
            grid: {
            borderColor: '#e7e7e7',
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
            },
            markers: {
            size: 1
            },
            xaxis: {
            categories: @json($bulan_pengeluaran),
            title: {
                text: 'Month'
            }
            },
            yaxis: {
            title: {
                text: 'Moving Average'
            },

            },
            legend: {
            position: 'top',
            horizontalAlign: 'right',
            floating: true,
            offsetY: -25,
            offsetX: -5
            }
        };
        var chartPengeluaran = new ApexCharts(document.querySelector("#myChartPengeluaran"), options_pengeluaran);
        chartPengeluaran.render();
    </script>
    @endpush
    <div class="p-4 sm:ml-64 pt-20 h-screen">
        <section class="p-5 overflow-y-auto mt-5">
            <div class="head lg:flex grid grid-cols-1 justify-between w-full">
                <div class="heading flex-auto">
                    <p class="text-blue-950 font-sm text-xs">
                        Perhitungan Data
                    </p>
                    <h2 class="font-bold tracking-tighter text-2xl text-theme-text">
                        {{ ucwords($title) }}
                    </h2>
                </div>
                <div>
                    <button data-modal-target="default-modal" data-modal-toggle="default-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                        Informasi Metode
                    </button>
                </div>
            </div>
            <div class="card bg-white p-5 mt-4 border rounded-md w-full relative overflow-x-auto">
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-styled-tab" data-tabs-toggle="#default-styled-tab-content" data-tabs-active-classes="text-purple-600 hover:text-purple-600 dark:text-purple-500 dark:hover:text-purple-500 border-purple-600 dark:border-purple-500" data-tabs-inactive-classes="dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300" role="tablist">
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-styled-tab" data-tabs-target="#styled-profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Perhitungan Pemasukan </button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="dashboard-styled-tab" data-tabs-target="#styled-dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">Perhitungan Pengeluaran</button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="pendapatan-styled-tab" data-tabs-target="#styled-pendapatan" type="button" role="tab" aria-controls="pendapatan" aria-selected="false">Perhitungan Pendapatan</button>
                    </li>

                </ul>
            </div>
            <div id="default-styled-tab-content">
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="head p-4 font-bold">
                        <h4>Table Perhitungan Moving Average Pemasukan</h4>
                        <hr>
                    </div>
                    <div>
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-3 border">Tahun (n)</th>
                                    <th class="px-4 py-3 border">Y</th>
                                    <th class="px-4 py-3 border">Fx</th>
                                    <th class="px-4 py-3 border">e</th>
                                    <th class="px-4 py-3 border">e²</th>
                                    <th class="px-4 py-3 border">|e|</th>
                                    <th class="px-4 py-3 border">|e|/Y</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($table_pemasukan as $entry)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-3 border">{{ $entry['tahun'] }}</td>
                                    <td class="px-4 py-3 border">{{ number_format($entry['nominal']) }}</td>
                                    <td class="px-4 py-3 border">{{ number_format($entry['Fx'], 2) }}</td>
                                    <td class="px-4 py-3 border">{{ number_format($entry['e'], 2) }}</td>
                                    <td class="px-4 py-3 border">{{ number_format($entry['e2'], 2) }}</td>
                                    <td class="px-4 py-3 border">{{ number_format($entry['|e|'], 2) }}</td>
                                    <td class="px-4 py-3 border">{{ number_format($entry['|e|/Y'], 2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="px-4 py-3 border font-bold" colspan="6">MSE (Mean Squared Error)</td>
                                    <td class="px-4 py-3 border">{{ number_format($mse, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 border font-bold" colspan="6">RMSE (Root Mean Squared Error)</td>
                                    <td class="px-4 py-3 border">{{ number_format($rmse, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 border font-bold" colspan="6">MAE (Mean Absolute Error)</td>
                                    <td class="px-4 py-3 border">{{ number_format($mae, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 border font-bold" colspan="6">MAPE (Mean Absolute Percentage Error)</td>
                                    <td class="px-4 py-3 border">{{ number_format($mape, 2) }}%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-800 border mt-5">
                        <div class="head p-4 font-bold">
                            <h4>Grafik Moving Average  Pemasukan</h4>
                            <hr>
                        </div>
                        <div style="width:100%;">
                            <div id="myChart"></div>
                        </div>
                    </div>
                </div>
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                    <div class="head p-4 font-bold">
                        <h4>Table Perhitungan Moving Average Pengeluaran</h4>
                        <hr>
                    </div>
                    <div>
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-3 border">Tahun (n)</th>
                                    <th class="px-4 py-3 border">Y</th>
                                    <th class="px-4 py-3 border">Fx</th>
                                    <th class="px-4 py-3 border">e</th>
                                    <th class="px-4 py-3 border">e²</th>
                                    <th class="px-4 py-3 border">|e|</th>
                                    <th class="px-4 py-3 border">|e|/Y</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($table_pengeluaran as $entry)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-3 border">{{ $entry['tahun'] }}</td>
                                    <td class="px-4 py-3 border">{{ number_format($entry['nominal']) }}</td>
                                    <td class="px-4 py-3 border">{{ number_format($entry['Fx'], 2) }}</td>
                                    <td class="px-4 py-3 border">{{ number_format($entry['e'], 2) }}</td>
                                    <td class="px-4 py-3 border">{{ number_format($entry['e2'], 2) }}</td>
                                    <td class="px-4 py-3 border">{{ number_format($entry['|e|'], 2) }}</td>
                                    <td class="px-4 py-3 border">{{ number_format($entry['|e|/Y'], 2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="px-4 py-3 border font-bold" colspan="6">MSE (Mean Squared Error)</td>
                                    <td class="px-4 py-3 border">{{ number_format($mse_pengeluaran, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 border font-bold" colspan="6">RMSE (Root Mean Squared Error)</td>
                                    <td class="px-4 py-3 border">{{ number_format($rmse_pengeluaran, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 border font-bold" colspan="6">MAE (Mean Absolute Error)</td>
                                    <td class="px-4 py-3 border">{{ number_format($mae_pengeluaran, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 border font-bold" colspan="6">MAPE (Mean Absolute Percentage Error)</td>
                                    <td class="px-4 py-3 border">{{ number_format($mape_pengeluaran, 2) }}%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-800 border mt-5">
                        <div class="head p-4 font-bold">
                            <h4>Table Perhitungan Moving Average Pemasukan</h4>
                            <hr>
                        </div>
                        <div style="width:100%;">
                            <div id="myChartPengeluaran"></div>
                        </div>
                    </div>
                </div>
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-pendapatan" role="tabpanel" aria-labelledby="pendapatan-tab">
                    <div class="head p-4 font-bold border">
                        <h4>Total Pendapatan = Moving Average Pemasukan − Moving Average Pengeluaran</h4>
                        <hr>
                        <h4>Total Pendapatan = Rp. {{ number_format($total_pendapatan,2, ",", ".") }}</h4>
                        <div class="mt-4">
                            @if ($total_pendapatan > 1000000)

                                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                                    <span class="font-medium">Keuntungan Pesan!</span>  Perusahaan mengalami keuntungan sebesar Rp. {{ number_format($total_pendapatan,2, ",", ".") }}.
                                </div>
                            @else
                                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                                    <span class="font-medium">Kerugian Pesan!</span> Perusahaan mengalami kerugian sebesar Rp. {{ number_format($total_pendapatan,2, ",", ".") }}.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>


            </div>
        </section>
    </div>
    <!-- Main modal -->
    <div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        METODE MOVING AVERAGE
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                        Moving average adalah suatu metode peramalan yang dilakukan dengan mengambil sekelompok nilai pengamatan, mencari nilai rata-rata tersebut sebagai ramalan untuk periode yang akan datang (Rachman, 2018). Metode Moving Averange mempunyai karakteristik khusus, yaitu :
                    </p>
                    <ul class="space-y-4 text-gray-500 list-disc list-inside dark:text-gray-400">
                        <li>Untuk menentukan ramalan pada periode yang akan datang memerlukan data historis selama jangka waktu tertentu. Misalnya dengan 3 bulan moving average, maka ramalan bulan ke 5 baru dapat dibuat setelah bulan ke 4 selesai/berakhir. Jika bulan moving verage bulan ke 7 baru bisa dibuat setelah bulan ke 6 berakhir.</li>
                        <li>
                            Semakin panjang jangka waktu moving average, efek pelicinan semakin terlihat dalam ramalan atau menghasilkan moving average yang semakin halus.
                            <br>
                            <p>Rumus moving average adalah sebagai berikut : </p>
                            <p>
                                MA= ΣXJumlah Periode
                                <br>
                                Keterangan :
                                <br>
                                MA	= Moving Average
                                <br>
                                ΣX	= Keseluruhan Penjumlahan dari semua data periode waktu yang diperhitungkan
                                <br>
                                Jumlah Periode = Jumlah periode rata-rata bergerak atau dapat ditulis dengan :
                                <br>
                                MA= n1+n2+n3+…n
                                <br>
                                Keterangan :
                                <br>
                                MA	= Moving Average
                                <br>
                                n1		= data periode pertama
                                <br>
                                n2		= data periode kedua
                                <br>
                                n3		= data periode ketiga dan seterusnya
                                <br>
                                n		= Jumlah periode rata-rata bergerak

                            </p>
                        </li>
                    </ul>

                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="default-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Close</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
