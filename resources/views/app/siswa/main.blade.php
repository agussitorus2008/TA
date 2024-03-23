@extends('layouts.main')

@section('title')
<h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">SELAMAT DATANG DI KAWALPTN-KU!</h6>
@endsection

@section('content')
<!-- Page header -->
<div class="page-header page-header-light shadow" style="background-color: #67AED4;">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title" style="font-size: 30px; color: #0A407F; margin:20px;">
                Ayo, bersiap dari sekarang<br>untuk mengejar PTN Impian!
            </h4>   
        </div>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">
    <div class="row justify-content-center">
        <div class="col-lg-4">
            <div class="card blog-horizontal text-center">
                <div class="card-body">
                    <div class=" mx-auto mb-3">
                        <img src="{{ asset('assets/images/Group 50.png') }}" alt="" class="img-fluid">
                    </div>
                    <h5 class="my-1"><p>Pendaftar {{$total_pendaftar}}</p></h5>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card blog-horizontal text-center">
                <div class="card-body">
                    <div class=" mx-auto mb-3">
                        <img src="{{ asset('assets/images/Vector.png') }}" alt="" class="img-fluid">
                    </div>
                    <h5 class="my-1"><p>Nilai rata-rata Tryout Pendaftar saat ini {{number_format($rata, 2) * 10}}</p></h5>
                </div>
            </div>
        </div>  
        
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-4">
            <div class="card blog-horizontal text-center">
                <div class="card-body">
                    <div class=" mx-auto mb-3">
                        <img src="{{ asset('assets/images/Vector (1).png') }}" alt="" class="img-fluid">
                    </div>
                    <h5 class="my-1"><p>{{$sekolah}} Sekolah </p></h5>
                </div>
            </div>
        </div>

        <div class="col-lg-4 ml-4">
            <div class="card blog-horizontal text-center">
                <div class="card-body">
                    <div class=" mx-auto mb-3">
                        <img src="{{ asset('assets/images/Vector.png') }}" alt="" class="img-fluid">
                    </div>
                    <h5 class="my-1"><p>{{number_format($max, 2) * 10}} Nilai Maksimum Tryout saat ini</p></h5>
                </div>
            </div>
        </div>  
        
    </div>
</div>
<!-- /content area -->
@endsection   
