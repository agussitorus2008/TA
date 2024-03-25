@extends('layouts.main')

@section('title')
    <h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">PROFIL SISWA</h6>
@endsection

@section('content')
<div class="content">
    <div class="container">
        <div class="card">
            <div class="card-body">
                @if($siswa == null)
                    <div class="row justify-content-center">
                        <h5 class="text-danger">Belum ada data siswa</h5>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col xs-4">
                        <a href="{{route('siswa.profile.main')}}" class="btn btn-warning">Tambah Data</a>
                    </div>
                    </div>
                @else
                <div class="row">
                    <div class="col-md-4">
                        <h5>NAMA LENGKAP</h5>
                    </div>
                    <div class="col-md-8">
                        <h5>: {{ $siswa->first_name }}</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <h5>ASAL SEKOLAH</h5>
                    </div>
                    <div class="col-md-8">
                        <h5>: {{ $siswa->asal_sekolah }}</h5> <!-- Fill with school information -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <h5>EMAIL</h5>
                    </div>
                    <div class="col-md-8">
                        <h5>: {{ $user->email }}</h5> <!-- Fill with email information -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <h5>PILIHAN 1</h5>
                    </div>
                    <div class="col-md-8">
                        <h5>: {{$siswa->pilihan1->nama_prodi_ptn}}</h5> <!-- Fill with choice 1 information -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <h5>PILIHAN 2</h5>
                    </div>
                    <div class="col-md-8">
                        <h5>: {{$siswa->pilihan2->nama_prodi_ptn}}</h5> <!-- Fill with choice 2 information -->
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div style="text-align: right;" class="mt-3">
            <a href="{{route('siswa.main')}}" class="btn btn-primary">Kembali</a>
        </div>
    </div>
</div>
@endsection
