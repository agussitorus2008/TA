@extends('layouts.main')

@section('title')
    Home - <span class="fw-normal">Siswa</span>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3 mt-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control form-control-lg" id="nama" name="nama">

                    <label for="nama" class="form-label mt-2">Asal Sekolah</label>
                    <input type="text" class="form-control form-control-lg" id="nama" name="nama">
                </div>
                <div class="col-md-6 mb-3 mt-3">
                    <label for="kelompok" class="form-label">Kelompok</label>
                    <input type="text" class="form-control form-control-lg" id="kelompok" name="kelompok">

                    <label for="nama" class="form-label mt-2">Provinsi Sekolah</label>
                    <input type="text" class="form-control form-control-lg" id="nama" name="nama">
                </div>
            </div>

            <div class="row justify-content-center mb-3">
                    <h4 class="text-center font-weight-bold">Pilihan Jurusan </h5>
            </div>


            <div class="row">
                <div class="col-md-6 mb-3">
                    <h3 class="text-center font-weight-bold">Pilihan 1</h5>
                    <label for="kampus1" class="form-label">Pilih Kampus 1</label>
                    <input type="text" class="form-control form-control-lg" id="kampus1" name="kampus1">

                    <label for="jurusan1" class="form-label mt-2">Pilih Jurusan 1</label>
                    <input type="text" class="form-control form-control-lg" id="jurusan1" name="jurusan1">
                </div>
                <div class="col-md-6 mb-3">
                    <h3 class="text-center font-weight-bold">Pilihan 2</h5>
                    <label for="kampus2" class="form-label">Pilih Kampus 2</label>
                    <input type="text" class="form-control form-control-lg" id="kampus2" name="kampus2">

                    <label for="jurusan2" class="form-label mt-2">Pilih Jurusan 2</label>
                    <input type="text" class="form-control form-contro  l-lg" id="jurusan2" name="jurusan2">
                </div>
            </div>

        </div>
    </div>
@endsection
