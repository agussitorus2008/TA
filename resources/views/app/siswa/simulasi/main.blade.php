@extends('layouts.main')

@section('title')
<h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">SIMULASI PROGRAM STUDI DAN PTN</h6>
@endsection
@section('content')
<div class="content">

    <form action="{{route('siswa.simulasi.test')}}" method="POST">
        @csrf
        <div class="container">
            {{-- <div class="row justify-content-center text-center">
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>PPU</h5>
                    <input type="number" class="form-control" name="ppu" value="{{ old('ppu') }}" required>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>PU</h5>
                    <input type="number" class="form-control" name="pu" value="{{ old('pu') }}" required>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>PM</h5>
                    <input type="number" class="form-control" name="pm" value="{{ old('pm') }}" required>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>PK</h5>
                    <input type="number" class="form-control" name="pk" value="{{ old('pk') }}" required>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>LBI</h5>
                    <input type="number" class="form-control" name="lbi" value="{{ old('lbi') }}" required>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>LBE</h5>
                    <input type="number" class="form-control" name="lbe" value="{{ old('lbe') }}" required>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>PBM</h5>
                    <input type="number" class="form-control" name="pbm" value="{{ old('pbm') }}" required>
                </div>
            </div> --}}
            @if($nilai == null)
                <div class="row justify-content-center text-center">
                    <div class="col-6 col-sm-4 col-md-3 col-lg-4 col-xl-4">
                        <h4 class="alert alert-danger">Anda Belum Memiliki Data Nilai</h4>
                    </div>
                </div>
            @else
            <div class="row">
                <div class="col-6 col-sm-4 col-md-3 col-lg-4 col-xl-4">
                    <h4>Nilai anda : {{ number_format($nilai, 2) * 10 }} dari {{ $nilaiCount }}x Tryout</h4>
                </div>
            </div>
            <hr>
        </div>
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-6 col-sm-4 col-md-3 col-lg-4 col-xl-4">
                    <h5>Program Studi dan PTN</h5>
                        <select name="prodi" class="form-control" id="select2">
                            @foreach($prodi as $p)
                            <option value="{{ $p->id_prodi }}">{{ $p->nama_prodi_ptn }}</option>
                            @endforeach
                        </select>
                </div>
                
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5></h5>
                    <button class="btn" type="submit" style="background-color: #0A407F; color:#fff">Simulasi</button>
                </div>
                <hr class="mt-4">
            </div>
        </div>
        @endif
    </form>

    <h3 id="kategori" class="text-center"></h3>

    <div class="row justify-content-center" id="result-section" style="display: none">
        <div class="col-2">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Peringkat saya</div>
                <div class="card-body">
                    <p class="card-text" id="peringkat"></p>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Pendaftar</div>
                <div class="card-body">
                    <p class="card-text" id="total_pendaftar"></p>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Daya Tampung</div>
                <div class="card-body">
                    <p class="card-text" id="daya_tampung"></p>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Nilai Saya</div>
                <div class="card-body">
                    <p class="card-text" id="nilai_saya"></p>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Nilai rata rata yang masuk</div>
                <div class="card-body">
                    <p class="card-text" id="nilai_rata"></p>
                </div>
            </div>
        </div>
    </div>
    

    <p id="rekomendasi-message" class="text-center"></p>
    <div id="rekomendasi-table" style="display: none;">
        <h4 class="text-center text-success">Berikut Rekomendasi Program studi dan PTN yang cocok untuk anda</h4>
        <div class="card">
            <table class="table datatable-basic">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Program Studi</th>
                        <th>PTN</th>
                        <th>Nilai Rata-Rata Yang Masuk</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div id="pagination-links"></div>
        </div>
    </div>
 
</div>

<script>
   $(document).ready(function() {
    $("#select2").select2();
    });
</script>


<!-- Add this script in your Blade view -->
<script>
    $(document).ready(function() {
        $('form').submit(function(event) {
            event.preventDefault();
    
            var formData = $(this).serialize();
            console.log("Form data being sent:", formData);
    
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData, 
                dataType: 'json',
    
                success: function(response) {
                    console.log("Server response:", response);
    
                    $('#kategori').text(response.kategori);
                    
                    if (response.kategori == "Macet Total" || response.kategori == "Macet") {
                        $('#kategori').css('color', 'red'); 
                    } else if (response.kategori == "Dipertimbangkan") {
                        $('#kategori').css('color', 'orange'); 
                    } else {
                        $('#kategori').css('color', 'green');
                    }
    
                    $('#peringkat').text(response.peringkat);
                    $('#total_pendaftar').text(response.total_pendaftar);
                    $('#daya_tampung').text(response.daya_tampung);
                    $('#nilai_saya').text((response.nilai_saya * 10).toLocaleString('en-US', { minimumFractionDigits: 2 }));
                    $('#nilai_rata').text((response.nilai_rata * 10).toLocaleString('en-US', { minimumFractionDigits: 2 }));
    
                    $('#result-section').show();
    
                    if (response.rekomendasi) {
                        // Display the rekomendasi table or error message
                        if (response.rekomendasi.data.length > 0) {
                            // Display the table
                            $('#rekomendasi-table tbody').empty();
                            $.each(response.rekomendasi.data, function(index, item) {
                                $('#rekomendasi-table tbody').append('<tr><td>' + (index + 1) + '</td><td>' + item.nama_prodi + '</td><td>' + item.nama_ptn + ' - ' + item.nama_singkat + '</td><td>' + (item.average_total_nilai * 10).toLocaleString('en-US', { minimumFractionDigits: 2 }) + '</td></tr>');
                            });
                            $('#rekomendasi-message').hide();
                            $('#rekomendasi-table').show();
    
                            // Handle pagination links
                            $('#pagination-links').html(response.rekomendasi.links);
                        } else {
                            // Display the error message
                            $('#rekomendasi-message')
                                .text('Maaf sepertinya tidak ada prodi yang cocok untuk kamu')
                                .addClass('alert alert-danger')
                                .show();
                            $('#rekomendasi-table').hide();
                            $('#pagination-links').empty();
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error details:", {
                        xhr: xhr,
                        status: status,
                        error: error
                    });
                    
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        var errorMessage = xhr.responseJSON.error;
                        $('#rekomendasi-message').text(errorMessage).addClass('alert alert-danger').show();
                    } else {
                        $('#rekomendasi-message').text("Terjadi kesalahan pada server").addClass('alert alert-danger').show();
                    }
                }
            });
        });
    });
    </script>
    

@endsection   
