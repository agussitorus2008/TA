@extends('layouts.main')

@section('title')
<h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">INFORMASI HASIL TRYOUT</h6>
@endsection

@section('content')

    <div id="error-message" style="display: none;">
    </div>

<div class="content">
    @if($errorMessage == 2)
        <div class="row justify-content-center text-center">
            <h5 class="text-danger alert alert-danger">Belum ada data Siswa</h5>
        </div>
        <div class="row justify-content-center text-center">
            <div class="col xs-4">
            <a href="{{route('siswa.profile.main')}}" class="btn btn-warning">Tambah Data</a>
        </div>
    @elseif($errorMessage == 0)
        <div class="row justify-content-center text-center">
            <h5 class="text-danger alert alert-danger">Belum ada data Nilai</h5>
        </div>
    @else
    
    <!-- Main charts -->
    <div class="row">
        <div class="col-xl-6">

            <!-- Traffic sources -->
            <div class="card" style="background-color: #3DA059;">
                <div class="card-header d-flex align-items-center">
                    <h5 class="text-white m-2">{{$totalPendaftar}} Siswa Pendaftar Saat Ini</h5>
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
                <h6>{{$siswa->sekolah_siswa->sekolah}}</h6>
            </div>
            <div class="col-xl-4">
                <h6>{{$siswa->kelompok_ujian}}</h6>
            </div>
            <hr>
        </div>
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
                <a href="{{ route('siswa.hasilTryout.pilihan1', ['nama_prodi' => $siswa->pilihan1->nama_prodi_ptn]) }}" target="_blank" style="text-decoration: none; color: inherit;">
                    <p style="display: inline;">
                        @if(is_int($peringkat11))
                            Peringkat <p style="color: #e32227;display: inline; font-weight:bold">{{ $peringkat11 }}</p> dari <p style="color: #3DA059;display: inline; font-weight:bold">{{$totalpendaftar11}}</p> Pendaftar Pilihan 1
                        @else
                            {{ $peringkat11 }}
                        @endif
                    </p>
                </a>
                <a href="{{ route('siswa.hasilTryout.pilihanTotal', ['nama_prodi' => $siswa->pilihan1->nama_prodi_ptn]) }}" target="_blank" style="text-decoration: none; color: inherit;"><p style="display: inline;">Peringkat <p style="color: #e32227;display: inline; font-weight:bold">{{ $peringkat1 }}</p> dari <p style="color: #3DA059;display: inline; font-weight:bold">{{$totalpendaftar1}}</p> Total Pendaftar</p></a>
            </div>
            <div class="col-xl-6">
                <h4>Pilihan 2</h4>
                <div class="card" style="background-color: #0A407F;">
                    <div class="card-header d-sm-flex align-items-center">
                        <h5 class="text-white m-2">{{$siswa->pilihan2->nama_prodi_ptn}} DAYA TAMPUNG: {{ $dayatampung2->daya_tampung }}</h5>
                    </div>
                </div>
                <a href="{{ route('siswa.hasilTryout.pilihan1', ['nama_prodi' => $siswa->pilihan2->nama_prodi_ptn]) }}" target="_blank" style="text-decoration: none; color: inherit;">
                    <p style="display: inline;">
                        @if(is_int($peringkat22))
                            Peringkat <p style="color: #e32227;display: inline; font-weight:bold">{{ $peringkat22 }}</p> dari <p style="color: #0A407F;display: inline; font-weight:bold">{{$totalpendaftar22}}</p> Pendaftar Pilihan 2
                        @else
                            {{ $peringkat22 }}
                        @endif
                    </p>
                </a>
                <a href="{{ route('siswa.hasilTryout.pilihanTotal', ['nama_prodi' => $siswa->pilihan2->nama_prodi_ptn]) }}" target="_blank" style="text-decoration: none; color: inherit;"><p style="display: inline;">Peringkat <p style="color: #e32227;display: inline; font-weight:bold">{{ $peringkat2 }}</p> dari <p style="color: #0A407F;display: inline; font-weight:bold">{{$totalpendaftar2}}</p> Total Pendaftar</p></a>
            </div>
            <hr>
        </div>
    </div>
    
    <div class="card">

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
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>

                @foreach($nilaito as $index => $nilai)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $nilai->tanggal }}</td>
                    <td>{{ $nilai->ppu_benar }}</td>
                    <td>{{ $nilai->pu_benar }}</td>
                    <td>{{ $nilai->pm_benar }}</td>
                    <td>{{ $nilai->pk_benar }}</td>
                    <td>{{ $nilai->lbi_benar }}</td>
                    <td>{{ $nilai->lbe_benar }}</td>
                    <td>{{ $nilai->pbm_benar }}</td>
                    {{-- <td>{{ number_format($total, 2) * 10 }}</td> --}}
                    <td>{{ $nilai->total_benar }}</td>
                    <td>{{ number_format($nilai->total_nilai, 2) * 10 }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="10"><p style="font-weight:bold; font-size:20px;">Nilai Rata - Rata</p></td>
                    <td><p style="font-weight:bold; font-size:20px;">{{ number_format($nilaiRata * 10, 2) }}</p></td>
                </tr>
            </tbody>
        </table>
    </div>  
    <div class="">
        <div id="chart"></div>
    </div>

    @endif


    {{-- <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-xl-2">
                <div class="card" style="background-color: #0A407F;">
                    <div class="align-items-center">
                        <h6 class="text-center m-2 text-white font-weight-bold">Pilihan Aktual</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center text-center">
            <div class="col-xl-6">
                <h4>Pilihan 1</h4>
                <div class="card" style="background-color: #3DA059;">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="text-white m-2">USU DAYA TAMPUNG: 2</h5>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <h4>Pilihan 2</h4>
                <div class="card" style="background-color: #0A407F;">
                    <div class="card-header d-sm-flex align-items-center">
                        <h5 class="text-white m-2">Ysy DAYA TAMPUNG: 2</h5>
                    </div>
                </div>
            </div>
            <hr>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-xl-6">
                <p>Peringkat 11 dari dari 21 Pendaftar Pilihan 1</p>
                <p>Peringkat 2 dari 30 Total Pendaftar</p>
            </div>
            <div class="col-xl-6">
                <p>Peringkat 11 dari dari 21 Pendaftar Pilihan 1</p>
                <p>Peringkat 2 dari 30 Total Pendaftar</p>
            </div>
            <hr>
        </div>
    </div> --}}
</div>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        try { 
            const totalData = {!! json_encode($totalData, JSON_NUMERIC_CHECK) !!};
            const rataData = {!! json_encode($rataData, JSON_NUMERIC_CHECK) !!};
            var tryoutCount = {!! $tryoutCount !!};
    
            var categories = Array.from({ length: tryoutCount }, (_, i) => (i + 1).toString());
    
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
                    categories: categories,
                    labels: {
                        x: -10
                    }
                },
                yAxis: {
                    allowDecimals: false
                },
                series: [{
                    name: 'Total Benar',
                    data: totalData,
                    color: '#3DA059'
                }, {
                    name: 'Nilai',
                    data: rataData,
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
        } catch (error) {
            console.error('An error occurred:', error.message);
            // Handle the error here
        }
    </script>


<script>
    fetch('{{ route('siswa.hasilTryout.main') }}') // Ganti dengan route yang benar
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Cek jika ada pesan error dalam respons
                if (data.error) {
                    // Tampilkan pesan error
                    document.getElementById('error-message').innerText = data.error;
                    document.getElementById('error-message').style.display = 'block';
                } else {
                    // Lanjutkan dengan menampilkan konten lain jika tidak ada error
                    document.getElementById('content').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                document.getElementById('error-message').style.display = 'block';
            });
</script>

@endsection   
