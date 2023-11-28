@extends('layouts.main')

@section('title')
    Home - <span class="fw-normal">Siswa</span>
@endsection

@section('content')
    <div class="card">
        <div class="card-body pb-0">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control form-control-lg" id="nama" name="nama">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kelompok" class="form-label">Kelompok</label>
                    <input type="text" class="form-control form-control-lg" id="kelompok" name="kelompok">
                </div>
            </div>

            <div class="row justify-content-center mb-3">
                <div class="col-md-6 text-center">
                    <h4 class="text-center font-weight-bold">Pilih Jurusan </h5>
                        <!-- Tambahkan opsi jurusan menggunakan dropdown atau radio button sesuai kebutuhan -->
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <h3 class="text-center font-weight-bold">Judul Pilihan 1</h5>
                </div>
                <div class="col-md-6 mb-3">
                    <h3 class="text-center font-weight-bold">Judul Pilihan 2</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kampus1" class="form-label">Pilih Kampus 1</label>
                    <input type="text" class="form-control form-control-lg" id="kampus1" name="kampus1">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kampus2" class="form-label">Pilih Kampus 2</label>
                    <input type="text" class="form-control form-control-lg" id="kampus2" name="kampus2">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="jurusan1" class="form-label">Pilih Jurusan 1</label>
                    <input type="text" class="form-control form-control-lg" id="jurusan1" name="jurusan1">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="jurusan2" class="form-label">Pilih Jurusan 2</label>
                    <input type="text" class="form-control form-contro  l-lg" id="jurusan2" name="jurusan2">
                </div>
            </div>
        </div>
    </div>
@endsection
