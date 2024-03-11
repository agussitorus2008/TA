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
            <div class="justifiy-content-center text-center">
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif    
            </div>    
        <form action="{{ route('siswa.profile.update', ['email' => auth()->user()->email]) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3 mt-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control form-control-lg" id="nama" name="nama" value="{{$user->nama}}">
                    <label for="nama" class="form-label mt-2">Asal Sekolah</label>
                    <select name="asal_sekolah" class="form-control" id="asal_sekolah">
                        @foreach($sekolah as $p)
                        <option value="{{ $p->sekolah }}" {{ ($selectedProdi->asal_sekolah == $p->sekolah) ? 'selected' : '' }}>
                            {{ $p->sekolah }}
                        </option>     
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3 mt-3">
                    <label for="kelompok" class="form-label">Kelompok</label>
                    <select name="kelompok_ujian" class="form-control" id="select2">
                        <option value="SAINTEK" {{ ($selectedProdi->kelompok_ujian == "SAINTEK") ? 'selected' : '' }}>SAINTEK</option>
                        <option value="SOSHUM" {{ ($selectedProdi->kelompok_ujian == "SOSHUM") ? 'selected' : '' }}>SOSHUM</option>
                    </select>

                    <label for="nama" class="form-label mt-2">Provinsi Sekolah</label>
                    <input type="text" name="provinsi_sekolah" class="form-control" id="provinsi_sekolah" readonly>
                </div>
            </div>

            <div class="row justify-content-center mb-3">
                    <h4 class="text-center font-weight-bold">Pilihan Jurusan</h5>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <h3 class="text-center font-weight-bold">Pilihan 1</h5>
                    <label for="kampus1" class="form-label">Pilih Prodi dan PTN 1</label>
                    <select name="pilihan1_utbk_aktual" class="form-control" id="pilihan1" required> 
                        @foreach($prodi as $p)
                        <option value="{{ $p->id_prodi }}" {{ ($selectedProdi->pilihan1_utbk_aktual == $p->id_prodi) ? 'selected' : '' }}>
                            {{ $p->nama_prodi_ptn }}
                        </option>                        
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <h3 class="text-center font-weight-bold">Pilihan 2</h5>
                    <label for="kampus2" class="form-label">Pilih Prodi dan PTN 2</label>
                    <select name="pilihan2_utbk_aktual" class="form-control" id="pilihan2" required>
                        @foreach($prodi as $p)
                        <option value="{{ $p->id_prodi }}" {{ ($selectedProdi->pilihan2_utbk_aktual == $p->id_prodi) ? 'selected' : '' }}>
                            {{ $p->nama_prodi_ptn }}
                        </option>                        
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row justify-content-center text-center">
                <div class="col-xs-6">
                    <button class="btn" type="submit" style="background-color: #0A407F; color:#fff">Simpan</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
    $("#asal_sekolah").select2({
        tags: true,
        createTag: function(params) {
            return params.term.trim() !== '' ? {
                id: params.term,
                text: params.term,
                newOption: true
            } : null;
        }
    });

    $("#asal_sekolah").on("select2:select", function(e) {
        if (e.params.data.newOption) {
            $('#provinsi_sekolah').removeAttr('readOnly');
            console.log("New option added:", e.params.data.text);
        }
    });

    $.ajax({
        url: '/add-sekolah/',
        method: 'POST',
        data: {
            asal_sekolah: $('#asal_sekolah').val(), 
            provinsi_sekolah: $('#provinsi_sekolah').val() 
        },
        success: function(response) {
            console.log('Data added successfully:', response);
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
            console.log(xhr.responseText);
        }
    });
});

         $(document).ready(function() {
         $("#pilihan1").select2();
         });

         $(document).ready(function() {
         $("#pilihan2").select2();
         });
     </script>
     <script>
        $(document).ready(function() {
        $('#asal_sekolah').change(function() {
        var selectedAsalSekolah = $(this).val();

        $.ajax({
            url: '/get-provinces/' + selectedAsalSekolah,
            method: 'GET',
            success: function(response) {
                $('#provinsi_sekolah').empty();

                $.each(response.propinsi, function(index, province) {
                    $('#provinsi_sekolah').val(province.propinsi);
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.log(xhr.responseText);
            }
        });
    });
});

 </script>
@endsection
