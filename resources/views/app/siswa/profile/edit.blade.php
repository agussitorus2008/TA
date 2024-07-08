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
                        <option value="{{ $p->id }}" {{ ($selectedProdi->asal_sekolah == $p->id) ? 'selected' : '' }}>
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
                    <input type="text" name="provinsi_sekolah" class="form-control" id="provinsi_sekolah" value="{{ $selectedProdi->asal_sekolah ? $provinsi->propinsi : ''}}" readonly>
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
                            <option value="{{ $ptns->id_ptn }}" 
                                {{ $selectedPTN1->id_ptn == $ptns->id_ptn ? 'selected' : '' }}>
                                {{ $ptns->nama_ptn }} - {{ $ptns->nama_singkat }}
                            </option>                         
                        @endforeach
                    </select>
                    <select name="prodi_piihan1" id="prodi_piihan1" class="form-control mt-3" required>
                        <option value="{{ $selectedPTN1 ? $selectedProdi1 : '' }}">
                            {{ $selectedPTN1 ? $prodi1->nama_prodi : 'Pilih Program Studi' }}
                        </option>                        
                    </select> 
                </div>
                <div class="col-md-6 mb-3">
                    <h3 class="text-center font-weight-bold">Pilihan 2</h5>
                    <label for="kampus2" class="form-label">Pilih Prodi dan PTN 2</label>
                    <select name="ptn_pilihan2" class="form-control" id="ptn_pilihan2" required> 
                        <option value="">Pilih PTN</option> 
                        @foreach($ptn as $ptns)
                        <option value="{{ $ptns->id_ptn }}" 
                            {{ ($selectedPTN2->id_ptn == $ptns->id_ptn) ? 'selected' : '' }}>
                            {{ $ptns->nama_ptn }} - {{ $ptns->nama_singkat }}
                        </option>                           
                        @endforeach
                    </select>
                    <select name="prodi_piihan2" id="prodi_piihan2" class="form-control mt-3" required>
                        <option value="{{ $selectedPTN2 ? $selectedProdi2 : '' }}">
                            {{ $selectedPTN2 ? $prodi2->nama_prodi : 'Pilih Program Studi' }}
                        </option>                       
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
        $("#pilihan1").select2();
        });

        $(document).ready(function() {
        $("#pilihan2").select2();
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
        // var selectedValue = e.params.data.id;

        // Cek apakah yang dipilih adalah opsi baru
        if (e.params.data.newOption) {
            $('#provinsi_sekolah').removeAttr('readOnly');
            console.log("New option added:", e.params.data.text);

            // Lakukan penambahan data ke database
            $.ajax({
                url: '/add-sekolah/',
                method: 'POST',
                data: { 
                    sekolah: $('#asal_sekolah').val() , 
                    propinsi: $('#provinsi_sekolah').val() 
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

{{-- <script>
    $(document).ready(function() {
        function fetchProdi(ptnId, callback) {
            $.ajax({
                url: '/get-prodi-from-ptn/' + ptnId,
                method: 'GET',
                success: function(response) {
                    callback(response);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    console.log(xhr.responseText);
                }
            });
        }

        // Fetch initial Prodi options if PTN is already selected
        var initialPtnId = $('#ptn_pilihan1').val();
        var initialPtnId = $('#ptn_pilihan2').val();
        
        if (initialPtnId) {
            fetchProdi(initialPtnId, function(response) {
                $('#prodi_piihan1').empty();
                $('#prodi_piihan1').append('<option value="">Pilih Program Studi</option>');
                $.each(response.prodi, function(index, prodi) {
                    $('#prodi_piihan1').append('<option value="' + prodi.id_prodi + '">' + prodi.nama_prodi + '</option>');
                });
            });
        }     
        if (initialPtnId) {
            fetchProdi(initialPtnId, function(response) {
                $('#prodi_piihan2').empty();
                $('#prodi_piihan2').append('<option value="">Pilih Program Studi</option>');
                $.each(response.prodi, function(index, prodi) {
                    $('#prodi_piihan2').append('<option value="' + prodi.id_prodi + '">' + prodi.nama_prodi + '</option>');
                });
            });
        }

        $('#ptn_pilihan1').change(function() {
            var selectedAsalSekolah = $(this).val();
            fetchProdi(selectedAsalSekolah, function(response) {
                $('#prodi_piihan1').empty();
                $('#prodi_piihan1').append('<option value="">Pilih Program Studi</option>');
                $.each(response.prodi, function(index, prodi) {
                    $('#prodi_piihan1').append('<option value="' + prodi.id_prodi + '">' + prodi.nama_prodi + '</option>');
                });
            });
        });

        $('#ptn_pilihan2').change(function() {
            var selectedAsalSekolah = $(this).val();
            fetchProdi(selectedAsalSekolah, function(response) {
                $('#prodi_piihan2').empty();
                $('#prodi_piihan2').append('<option value="">Pilih Program Studi</option>');
                $.each(response.prodi, function(index, prodi) {
                    $('#prodi_piihan2').append('<option value="' + prodi.id_prodi + '">' + prodi.nama_prodi + '</option>');
                });
            });
        });
    });

</script> --}}

@endsection
