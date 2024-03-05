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
            <form action="">
            <div class="row">
                <div class="col-md-6 mb-3 mt-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control form-control-lg" id="nama" name="nama" value="{{$user->nama}}" readonly>
                    <label for="nama" class="form-label mt-2">Asal Sekolah</label>
                    <select name="asal_sekolah" class="form-control" id="select2">
                        @foreach($sekolah as $p)
                        <option value="{{ $p->id }}">{{ $p->sekolah }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3 mt-3">
                    <label for="kelompok" class="form-label">Kelompok</label>
                    <select name="kelompok_ujian" class="form-control" id="select2">
                        <option value="SAINTEK">SAINTEK</option>
                        <option value="SOSHUM">SOSHUM</option>
                    </select>

                    <label for="nama" class="form-label mt-2">Provinsi Sekolah</label>
                    <select name="provinsi_sekolah" class="form-control" id="provinsi_sekolah" readonly>

                    </select>
                </div>
            </div>

            <div class="row justify-content-center mb-3">
                    <h4 class="text-center font-weight-bold">Pilihan Jurusan</h5>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <h3 class="text-center font-weight-bold">Pilihan 1</h5>
                    <label for="kampus1" class="form-label">Pilih Prodi dan PTN 1</label>
                    <select name="pilihan1_utbk_aktual" class="form-control" id="select2">
                        @foreach($prodi as $p)
                        <option value="{{ $p->id_prodi }}">{{ $p->nama_prodi_ptn }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <h3 class="text-center font-weight-bold">Pilihan 2</h5>
                    <label for="kampus2" class="form-label">Pilih Prodi dan PTN 2</label>
                    <select name="pilihan2_utbk_aktual" class="form-control" id="select2">
                        @foreach($prodi as $p)
                        <option value="{{ $p->id_prodi }}">{{ $p->nama_prodi_ptn }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row justify-content-center">
                <button class="btn" type="submit" style="background-color: #0A407F; color:#fff">Simpan</button>
            </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
         $("#select2").select2();
         });
     </script>
     <script>
        $(document).ready(function() {
            // Listen for changes in the "Asal Sekolah" dropdown
            $('#asal_sekolah').change(function() {
                var selectedAsalSekolah = $(this).val();

                $.ajax({
                    url: '/get-provinces/' + selectedAsalSekolah,
                    method: 'GET',
                    success: function(response) {
                        $('#provinsi_sekolah').empty();
    
                        $('#provinsi_sekolah').append('<option value="' + response.provinces + '">' + response.provinces + '</option>');
                    },
                    error: function(error) {
                        var errorMessage = error.responseJSON.error;
                        // $('#provinsi_sekolah').append('<option value=" class="text-danger"'  + errorMessage + '">' + errorMessage + '</option>');;
                        console.error('Error:', errorMessage);
                    }
                });
            });
        });
    </script>
@endsection
