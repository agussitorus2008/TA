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
        <form action="{{ route('siswa.profile.add', ['email' => auth()->user()->email]) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3 mt-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control form-control-lg" id="nama" name="nama" value="{{$user->nama}}" required>
                    <label for="nama" class="form-label mt-2">Asal Sekolah</label>
                    <select name="asal_sekolah" class="form-control" id="asal_sekolah">
                        <option value="">Pilih Sekolah Anda</option> 
                        @foreach($sekolah as $p)
                        <option value="{{ $p->id }}">{{ $p->sekolah }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3 mt-3">
                    <label for="kelompok" class="form-label">Kelompok</label>
                    <select name="kelompok_ujian" class="form-control" id="select2" required>
                        <option value="">Pilih Program Studi</option>   
                        <option value="SAINTEK">SAINTEK</option>
                        <option value="SOSHUM">SOSHUM</option>
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
                    <select name="ptn_pilihan1" class="form-control" id="ptn_pilihan1" required> 
                        <option value="">Pilih PTN</option> 
                        @foreach($ptn as $ptns)
                            <option value="{{ $ptns->id_ptn }}">
                                {{ $ptns->nama_ptn }} - {{ $ptns->nama_singkat }}
                            </option>                        
                        @endforeach
                    </select>
                    <select name="prodi_piihan1" id="prodi_piihan1" class="form-control mt-3" required>
                        <option value="">Pilih Program Studi</option>                        
                    </select> 
                    {{-- <select name="pilihan1_utbk_aktual" class="form-control" id="pilihan1" required> 
                        @foreach($prodi as $p)
                        <option value="{{ $p->id_prodi }}">{{ $p->nama_prodi_ptn }}</option>
                        @endforeach
                    </select> --}}
                </div>
                <div class="col-md-6 mb-3">
                    <h3 class="text-center font-weight-bold">Pilihan 2</h5>
                    <label for="kampus2" class="form-label">Pilih Prodi dan PTN 2</label>
                    <select name="ptn_pilihan2" class="form-control" id="ptn_pilihan2" required> 
                        <option value="">Pilih PTN</option> 
                        @foreach($ptn as $ptns)
                        <option value="{{ $ptns->id_ptn }}"> 
                            {{ $ptns->nama_ptn }} - {{ $ptns->nama_singkat }}
                        </option>                        
                        @endforeach
                    </select>
                    <select name="prodi_piihan2" id="prodi_piihan2" class="form-control mt-3" required>
                        <option value="">Pilih Program Studi</option>                        
                    </select>
                    {{-- <select name="pilihan2_utbk_aktual" class="form-control" id="pilihan2" required>
                        @foreach($prodi as $p)
                        <option value="{{ $p->id_prodi }}">{{ $p->nama_prodi_ptn }}</option>
                        @endforeach
                    </select> --}}
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
            // Mengambil nilai dan teks opsi baru yang ditambahkan
            var newValue = e.params.data.id;
            var newText = e.params.data.text;

            // Menghapus atribut readOnly dari dropdown provinsi_sekolah
            $('#provinsi_sekolah').removeAttr('readonly');

            // Menampilkan pesan log bahwa opsi baru ditambahkan
            console.log("New option added:", newText);

            // Menjalankan permintaan AJAX untuk menambahkan sekolah
            $.ajax({
                url: '/add-sekolah/',
                method: 'POST',
                data: {
                    asal_sekolah: newValue,
                    provinsi_sekolah: $('#provinsi_sekolah').val() 
                },
                success: function(response) {
                    console.log('Data added successfully:', response);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    // Menampilkan pesan error dari respons JSON
                    var errorMessage = xhr.responseJSON.error;
                    console.log(errorMessage); // Lakukan penanganan error yang sesuai di sini
                }
            });
        }
    });
});
         $(document).ready(function() {
        $("#ptn_pilihan1").select2();
        });

        $(document).ready(function() {
        $("#ptn_pilihan2").select2();
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
<script>
    $(document).ready(function() {
        $('#ptn_pilihan1').change(function() {
            var idPtn = $(this).val();
            console.log('Selected PTN:', idPtn);

            $.ajax({
                url: '/get-prodi-from-ptn/' + idPtn,
                method: 'GET',
                success: function(response) {
                    console.log('AJAX Success:', response);
                    if (response.prodi && response.prodi.length > 0) {
                        $('#prodi_piihan1').empty();
                        $('#prodi_piihan1').append('<option value="">Pilih Program Studi</option>');

                        $.each(response.prodi, function(index, prodi) {
                            $('#prodi_piihan1').append('<option value="' + prodi.id_prodi + '">' + prodi.nama_prodi + '</option>');
                        });
                    } else {
                        console.warn('No prodi found for the selected PTN.');
                        $('#prodi_piihan1').empty();
                        $('#prodi_piihan1').append('<option value="">Tidak Ada Program Studi</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    console.log('Response Text:', xhr.responseText);

                    // Display a friendly message to the user
                    $('#prodi_piihan1').empty();
                    $('#prodi_piihan1').append('<option value="">Error loading programs</option>');
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#ptn_pilihan2').change(function() {
            var idPtn = $(this).val();
            console.log('Selected PTN:', idPtn);

            $.ajax({
                url: '/get-prodi-from-ptn/' + idPtn,
                method: 'GET',
                success: function(response) {
                    console.log('AJAX Success:', response);
                    if (response.prodi && response.prodi.length > 0) {
                        $('#prodi_piihan2').empty();
                        $('#prodi_piihan2').append('<option value="">Pilih Program Studi</option>');

                        $.each(response.prodi, function(index, prodi) {
                            $('#prodi_piihan2').append('<option value="' + prodi.id_prodi + '">' + prodi.nama_prodi + '</option>');
                        });
                    } else {
                        console.warn('No prodi found for the selected PTN.');
                        $('#prodi_piihan2').empty();
                        $('#prodi_piihan2').append('<option value="">Tidak Ada Program Studi</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    console.log('Response Text:', xhr.responseText);

                    // Display a friendly message to the user
                    $('#prodi_piihan2').empty();
                    $('#prodi_piihan2').append('<option value="">Error loading programs</option>');
                }
            });
        });
    });
</script>

@endsection

