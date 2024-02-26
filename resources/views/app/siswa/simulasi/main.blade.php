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
                <h5></h5>
                <button class="btn" type="submit" style="background-color: #0A407F; color:#fff">Generate</button>
            </div>
            <hr>
        </div>
    </div>

    <p class="text-center">Berikut rekomendasi PTN dan Program Studi yang cocok untuk Anda</p>
    
    <div class="card">
        <table class="table datatable-basic">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Program Studi</th>
                    <th>PTN</th>
                    <th>Rata-Rata</th>
                </tr>
            </thead>
            <tbody>
        
            </tbody>
        </table>
    </div>  

</div>
@endsection   
