@extends('layouts.main')

@section('title')
    @if(empty($siswa))
        <h6 style="font-size:18px" class="text-danger d-none d-sm-inline-block h-16px ms-3">Lengkapi Data Diri dan Jurusan Pilihan Anda!</h6>
    @else
        <h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">DATA DIRI DAN PILIHAN JURUSAN</h6>
    @endif
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(empty($siswa))
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('siswa.profile.add', ['email' => $user->email]) }}" class="btn btn-primary">Tambah Data</a>
            </div>
            @else
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('siswa.profile.edit', ['email' => $user->email]) }}" class="btn btn-primary">Ubah Data</a>
            </div>
            @endif

            <div class="row">
                <div class="col-md-6 mb-3 mt-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control form-control-lg" id="nama" name="nama" value="{{ $siswa ? $siswa->first_name : $user->nama }}" readonly>
                    @if(empty($siswa))
                    <label for="nama" class="form-label mt-2">Asal Sekolah</label>
                    <input type="text" class="form-control form-control-lg text-danger" id="nama" name="nama" value="Belum ada data siswa" readonly>
                </div>
                <div class="col-md-6 mb-3 mt-3">
                    <label for="kelompok" class="form-label">Kelompok</label>
                    <input type="text" class="form-control form-control-lg text-danger" id="kelompok" name="kelompok" value="Belum ada data siswa" readonly>

                    <label for="nama" class="form-label mt-2">Provinsi Sekolah</label>
                    <input type="text" class="form-control form-control-lg text-danger" id="nama" name="nama" value="Belum ada data siswa" readonly>
                    @else
                    <label for="nama" class="form-label mt-2">Asal Sekolah</label>
                    <input type="text" class="form-control form-control-lg" id="nama" name="nama" value="{{$siswa->asal_sekolah}}" readonly>
                </div>
                <div class="col-md-6 mb-3 mt-3">
                    <label for="kelompok" class="form-label">Kelompok</label>
                    <input type="text" class="form-control form-control-lg" id="kelompok" name="kelompok" value="{{$siswa->kelompok_ujian}}" readonly>

                    <label for="nama" class="form-label mt-2">Provinsi Sekolah</label>
                    <input type="text" class="form-control form-control-lg" id="nama" name="nama" value="{{$propinsi}}" readonly>
                    @endif
                </div>
            </div>

            <div class="row justify-content-center mb-3">
                <div class="col-xl-2">
                    <div class="card" style="background-color: #0A407F;">
                        <div class="align-items-center">
                            <h6 class="text-center m-2 text-white font-weight-bold">Pilihan Jurusan</h6>
                        </div>
                    </div>
                </div>
            </div>

            @if(!empty($siswa))
            <div class="row justify-content-center text-center">
                <div class="col-xl-6">
                    <h4>Pilihan 1</h4>
                    <div class="card" style="background-color: #3DA059;">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="text-white m-2">{{$siswa->pilihan1->nama_prodi_ptn}}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <h4>Pilihan 2</h4>
                    <div class="card" style="background-color: #0A407F;">
                        <div class="card-header d-sm-flex align-items-center">
                            <h5 class="text-white m-2">{{$siswa->pilihan2->nama_prodi_ptn}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-xl-6">
                    <h4>Pilihan 1</h4>
                    <div class="card" style="background-color: #3DA059;">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="text-white m-2">Belum ada data siswa</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <h4>Pilihan 2</h4>
                    <div class="card" style="background-color: #0A407F;">
                        <div class="card-header d-sm-flex align-items-center">
                            <h5 class="text-white m-2">Belum ada data siswa</h5>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
@endsection
