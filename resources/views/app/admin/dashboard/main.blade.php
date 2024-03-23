@extends('layouts_admin.main')

@section('title')
<h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">SELAMAT DATANG DI KAWALPTN-KU!</h6>
@endsection

@section('content')
<div class="page-header page-header-light shadow" style="background-color: #67AED4;">
    <div class="page-header-content">
        <div class="row justify-content-center text-center mt-4">
            <div class="col-lg-3">
                <div class=" mx-auto mb-3">
                    <img src="{{ asset('assets/images/Group 50.png') }}" alt="" class="img-fluid">
                </div>
                <h1 class="my-1 text-white"><p>{{$total_pendaftar}}</p></h1>
                <h4 class="my-1 text-white"><p>Pendaftar</p></h4>
            </div>

            <div class="col-lg-3">
                <div class=" mx-auto mb-3">
                    <img src="{{ asset('assets/images/Vector (1).png') }}" alt="" class="img-fluid">
                </div>
                <h1 class="my-1 text-white"><p>{{{$sekolah}}}</p></h1>
                <h4 class="my-1 text-white"><p>Sekolah</p></h4>
            </div>

            <div class="col-lg-3">
                <div class=" mx-auto mb-3">
                    <img src="{{ asset('assets/images/Vector.png') }}" alt="" class="img-fluid">
                </div>
                <h1 class="my-1 text-white"><p>{{number_format($rata, 2) * 10}}</p></h1>
                <h4 class="my-1 text-white"><p>Nilai rata-rata Tryout Pendaftar saat ini </p></h4>
            </div>  

            <div class="col-lg-3">
                <div class=" mx-auto mb-3">
                    <img src="{{ asset('assets/images/Vector.png') }}" alt="" class="img-fluid">
                </div>
                <h1 class="my-1 text-white"><p>{{number_format($max, 2) * 10}}</p></h1>
                <h4 class="my-1 text-white"><p>Nilai Maksimum Tryout saat ini</p></h4>
            </div>  
        </div>
    </div>
</div>

<div class="mt-4">
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
            categories: ['2020', '2021', '2022', '2023'],
            labels: {
                x: -10
            }
        },

        yAxis: {
            allowDecimals: false,
        },
        

        series: [{
            name: 'Nilai rata-rata',
            data: [900, 510, 600, 750],
            color: '#3DA059'
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
@endsection
