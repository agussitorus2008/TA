@extends('layouts.main')

@section('title')
<h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">INFORMASI HASIL TRYOUT</h6>
@endsection

@section('content')
<div class="content">

    <!-- Main charts -->
    <div class="row">
        <div class="col-xl-6">

            <!-- Traffic sources -->
            <div class="card" style="background-color: #3DA059;">
                <div class="card-header d-flex align-items-center">
                    <h5 class="text-white m-2">{{count($totalPendaftar)}} Siswa Pendaftar Saat Ini</h5>
                </div>
            </div>
            <!-- /traffic sources -->

        </div>

        <div class="col-xl-6">
            <!-- Sales stats -->
            <div class="card" style="background-color: #0A407F;">
                <div class="card-header d-sm-flex align-items-center">
                    <h5 class="text-white m-2">{{$totalSekolah}} Jumlah Sekolah Pendaftar</h5>
                </div>
            </div>
            <!-- /sales stats -->

        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center text-center">
            <hr>
            <div class="col-xl-4">
                <h6>{{$siswa->first_name}}</h6>
            </div>
            <div class="col-xl-4">
                <h6>{{$siswa->asal_sekolah}}</h6>
            </div>
            <div class="col-xl-4">
                <h6>{{$siswa->kelompok_ujian}}</h6>
            </div>
            <hr>
        </div>
    </div>

    <div id="error-message" style="display: none;">
        <!-- Error message will be displayed here -->
    </div>

    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-xl-6">
                <h4>Pilihan 1</h4>
                <div class="card" style="background-color: #3DA059;">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="text-white m-2">{{$siswa->pilihan1->nama_prodi_ptn}} DAYA TAMPUNG: {{ $dayatampung1->daya_tampung }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <h4>Pilihan 2</h4>
                <div class="card" style="background-color: #0A407F;">
                    <div class="card-header d-sm-flex align-items-center">
                        <h5 class="text-white m-2">{{$siswa->pilihan2->nama_prodi_ptn}} DAYA TAMPUNG: {{ $dayatampung2->daya_tampung }}</h5>
                    </div>
                </div>
            </div>
            <hr>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-xl-6">
                <p>Peringkat {{ $peringkat11 }} dari dari {{$totalpendaftar11}} Pendaftar Pilihan 1</p>
                <p>Peringkat {{ $peringkat1 }} dari {{$totalpendaftar1}} Total Pendaftar</p>
            </div>
            <div class="col-xl-6">
                <p>Peringkat {{$peringkat22}} dari dari {{$totalpendaftar22}} Pendaftar Pilihan 1</p>
                <p>Peringkat {{$peringkat2}} dari {{$totalpendaftar2}} Total Pendaftar</p>
            </div>
            <hr>
        </div>
    </div>
    
    <div class="card">
        {{-- <div class="card-header">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group form-inline">
                        <label for="show" class="mr-2">Show per page:</label>
                        <select class="form-control" id="show" name="show">
                            <option value="10">5</option>
                            <option value="20">10</option>
                        </select>
                    </div>
                </div>
            </div>
        </div> --}}

        <table class="table datatable-basic">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>PPU</th>
                    <th>PU</th>
                    <th>PM</th>
                    <th>PK</th>
                    <th>LBI</th>
                    <th>LBE</th>
                    <th>PBM</th>
                    <th>TOTAL</th>
                    <th>Rata-Rata</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nilaito as $index => $nilai)
                <tr>
                <?php
                    $total = 0;
                    $total += ($nilai->ppu * $bobot_ppu + $nilai->pu * $bobot_pu + $nilai->pm * $bobot_pm + $nilai->pk * $bobot_pk + $nilai->lbi * $bobot_lbi + $nilai->lbe * $bobot_lbe + $nilai->pbm * $bobot_pbm);
                    $rata = $total / 7;
                ?>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($nilai->tanggal)->format('d-m-y') }}</td>
                    <td>{{ $nilai->ppu }}</td>
                    <td>{{ $nilai->pu }}</td>
                    <td>{{ $nilai->pm }}</td>
                    <td>{{ $nilai->pk }}</td>
                    <td>{{ $nilai->lbi }}</td>
                    <td>{{ $nilai->lbe }}</td>
                    <td>{{ $nilai->pbm }}</td>
                    <td>{{ number_format($total, 2) * 10 }}</td>
                    <td>{{ number_format($rata, 2) * 10 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>  
    <div class="">
        <div id="chart"></div>
    </div>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        const chart = Highcharts.chart('chart', {
        chart: {
            type: 'column'
        },

        legend: {
            align: 'right',
            verticalAlign: 'middle',
            layout: 'vertical'
        },

        xAxis: {
            categories: ['1/10', '2/10', '3/10', '4/10', '5/10', '6/10'],
            labels: {
                x: -10
            }
        },

        yAxis: {
            allowDecimals: false,
        },
        

        series: [{
            name: 'Pilihan 1',
            data: [900, 510, 600, 750, 500, 800],
            color: '#3DA059'
        }, {
            name: 'Pilihan 2',
            data: [800, 700, 600, 500, 648, 790],
            color: '#0A407F'
        }],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        align: 'center',
                        verticalAlign: 'bottom',
                        layout: 'horizontal'
                    },
                    yAxis: {
                        labels: {
                            align: 'left',
                            x: 0,
                            y: -5
                        },
                        title: {
                            text: null
                        }
                    },
                    subtitle: {
                        text: null
                    },
                    credits: {
                        enabled: false
                    }
                }
            }]
        }
        });

        document.getElementById('small').addEventListener('click', function () {
        chart.setSize(400);
        });

        document.getElementById('large').addEventListener('click', function () {
        chart.setSize(600);
        });

        document.getElementById('auto').addEventListener('click', function () {
        chart.setSize(null);
        });

    </script>
    <script>
    // Make an AJAX request to check for the error
    fetch('{{ route('siswa.hasilTryout.main') }}')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Check if there is an error message
            if (data.error) {
                // Display the error message
                document.getElementById('error-message').innerText = data.error;
                document.getElementById('error-message').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
</script>


</div>
@endsection   
