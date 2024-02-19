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
                    <h5 class="text-white m-2">12,345 Siswa Pendaftar Saat Ini</h5>
                </div>
            </div>
            <!-- /traffic sources -->

        </div>

        <div class="col-xl-6">
            <!-- Sales stats -->
            <div class="card" style="background-color: #0A407F;">
                <div class="card-header d-sm-flex align-items-center">
                    <h5 class="text-white m-2">1,234 Jumlah Sekolah Pendaftar</h5>
                </div>
            </div>
            <!-- /sales stats -->

        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center text-center">
            <hr>
            <div class="col-xl-4">
                <h6>Lasroha M Panjaitan</h6>
            </div>
            <div class="col-xl-4">
                <h6>SMA ST. PETRUS SIDIKALANG</h6>
            </div>
            <div class="col-xl-4">
                <h6>SAINTEK</h6>
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
                        <h5 class="text-white m-2">T. MESIN - ITB DAYA TAMPUNG:20</h5>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <h4>Pilihan 2</h4>
                <div class="card" style="background-color: #0A407F;">
                    <div class="card-header d-sm-flex align-items-center">
                        <h5 class="text-white m-2">KEDOKTERAN - UI DAYA TAMPUNG:75</h5>
                    </div>
                </div>
            </div>
            <hr>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-xl-6">
                <p>Peringkat 1 dari dari 500 Pendaftar Pilihan 1</p>
                <p>Peringkat 1 dari 700 Total Pendaftar</p>
            </div>
            <div class="col-xl-6">
                <p>Peringkat 3 dari dari 800 Pendaftar Pilihan 1</p>
                <p>Peringkat 1 dari 1000 Total Pendaftar</p>
            </div>
            <hr>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
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
        </div>

        <table class="table datatable-basic">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>PPU</th>
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
            data: [900, 510, 600, 750, 500, 800]
        }, {
            name: 'Pilihan 2',
            data: [800, 700, 600, 500, 648, 790]
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

</div>
@endsection   
