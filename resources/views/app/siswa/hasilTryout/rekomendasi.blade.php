@extends('layouts.main')

@section('title')
<h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">REKOMENDASI PROGRAM STUDI DAN PTN</h6>
@endsection

@section('content')
    <div class="content mt-5">
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
        @elseif($rekomendasi == null)
        <div class="row justify-content-center text-center">
            <h5 class="text-danger alert alert-danger">Maaf Tidak Ada Rekomendasi yang Cocok Untukmu</h5>
        </div>
        @elseif($nilai == null)
        <div class="row justify-content-center text-center">
            <h5 class="text-danger alert alert-danger">Belum ada data siswa</h5>
        </div>
        <div class="row justify-content-center">
            <div class="col xs-4">
            <a href="{{route('siswa.profile.main')}}" class="btn btn-warning">Tambah Data</a>
        </div>
        </div>
        @else
        <div class="container">
            <div class="row justify-content-center text-center">
                <h4 class="text-center text-success mb-4">Berikut Rekomendasi Program Studi dan Perguruan Tinggi Negeri yang Cocok Untuk Anda</h4>
                <div class="col-xl-6">
                    <h4>Pilihan 1</h4>
                    <div class="card" style="background-color: #3DA059;">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="text-white m-2">{{ $rekomendasi[0]->nama_prodi_ptn }}  DAYA TAMPUNG: {{ $rekomendasi[0]->daya_tampung }}</h5>
                        </div>
                    </div>
                    <a href="{{ route('siswa.hasilTryout.pilihanTotal', ['nama_prodi' => $rekomendasi[0]->nama_prodi_ptn]) }}" target="_blank" style="text-decoration: none; color: inherit;">
                        <p style="display: inline;">
                            Peringkat <p style="color: #e32227;display: inline; font-weight:bold">{{ $rekomendasi[0]->peringkat }}</p> dari <p style="color: #3DA059;display: inline; font-weight:bold">{{$rekomendasi[0]->total_applicants + 1}}</p> Pendaftar
                        </p>
                    </a>
                </div>
                <div class="col-xl-6">
                    <h4>Pilihan 2</h4>
                    <div class="card" style="background-color: #0A407F;">
                        <div class="card-header d-sm-flex align-items-center">
                            <h5 class="text-white m-2">{{ $rekomendasi[1]->nama_prodi_ptn }} DAYA TAMPUNG: {{ $rekomendasi[1]->daya_tampung }}</h5>
                        </div>
                    </div>
                    <a href="{{ route('siswa.hasilTryout.pilihanTotal', ['nama_prodi' => $rekomendasi[1]->nama_prodi_ptn]) }}" target="_blank" style="text-decoration: none; color: inherit;">
                        <p style="display: inline;">
                            Peringkat <p style="color: #e32227;display: inline; font-weight:bold">{{ $rekomendasi[1]->peringkat }}</p> dari <p style="color: #0A407F;display: inline; font-weight:bold">{{$rekomendasi[1]->total_applicants + 1}}</p> Pendaftar
                        </p>
                    </a>
                </div>
                <hr>
            </div>
        </div>
        @endif
    </div>
@endsection
