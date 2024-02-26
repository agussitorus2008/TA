@extends('layouts.main')

@section('title')
<h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">SIMULASI</h6>
@endsection
@section('content')
<div class="content">

    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-xl-2">
                <h5>PPU</h5>
                <input type="number">
            </div>
            <div class="col-xl-2">
                <h5>PPU</h5>
                <input type="number">
            </div>
            <div class="col-xl-2">
                <h5>PPU</h5>
                <input type="number">
            </div>
            <div class="col-xl-2">
                <h5>PPU</h5>
                <input type="number">
            </div>
            <div class="col-xl-2">
                <h5>PPU</h5>
                <input type="number">
            </div>
            <div class="col-xl-2">
                <h5>PPU</h5>
                <input type="number">
            </div>
            <hr>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-xl-4">
                <h5>Program Studi</h5>
                <input type="text" name="" id="">
            </div>
            <div class="col-xl-4">
                <h5>PTN</h5>
                <input type="text" name="" id="">
            </div>
            <div class="col-xl-4">
                <button class="btn" type="submit" style="background-color: #0A407F; color:#fff">Submit</button>
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

</div>
@endsection   
