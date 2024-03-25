@extends('layouts.main')

@section('title')
<h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">SIMULASI PTN</h6>
@endsection
@section('content')
<div class="content">

    <form action="{{route('siswa.simulasi.test_ptn')}}" method="POST">
        @csrf
        <div class="container">
            <div class="row justify-content-center text-center">
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
            </div>
            <hr>
        </div>
        <div class="container">
            <div class="row justify-content-center text-center">
                {{-- <div class="col-6 col-sm-4 col-md-3 col-lg-4 col-xl-4">
                    <h5>JENIS</h5>
                    <select name="jenis" class="form-control">
                        <option value="SAINTEK">SAINTEK</option>
                        <option value="SOSHUM">SOSHUM</option>
                    </select>
                </div> --}}
                <div class="col-6 col-sm-4 col-md-3 col-lg-4 col-xl-4">
                    <h5>PTN</h5>
                        <select name="ptn" class="form-control" id="select2">
                            @foreach($ptn as $p)
                            <option value="{{ $p->id_ptn }}">{{$p->nama_ptn}} - {{ $p->nama_singkat }}</option>
                            @endforeach
                        </select>
                </div>
                
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5></h5>
                    <button class="btn" type="submit" style="background-color: #0A407F; color:#fff">Generate</button>
                </div>
                <hr>
            </div>
        </div>
    </form>

    {{-- <h3 id="kategori" class="text-center"></h3>
    Peringkat saya :<p id="peringkat" ></p>Total Pendaftar :<p id="total_pendaftar" ></p>
    Nilai Saya :<p id="nilai_saya" ></p> Nilai rata rata yang masuk :<p id="nilai_rata"></p> --}}

    <p id="rekomendasi-message" class="text-center text-danger"></p>
    <div id="rekomendasi-table" style="display: none;">
        <h4 class="text-center text-success">Berikut Rekomendasi Program studi yang cocok untuk anda berdasarkan PTN yang anda inginkan</h4>
        <div class="card">
            <table class="table datatable-basic">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Program Studi</th>
                        <th>PTN</th>
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

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json',

            success: function(response) {

                if (response.rekomendasi) {
                    // Display the rekomendasi table or error message
                    if (response.rekomendasi.data.length > 0) {
                        // Display the table
                        $('#rekomendasi-table tbody').empty();
                        $.each(response.rekomendasi.data, function(index, item) {
                            $('#rekomendasi-table tbody').append('<tr><td>' + (index + 1) + '</td><td>' + item.nama_prodi + '</td><td>' + item.nama_singkat + '</td></tr>');
                        });
                        $('#rekomendasi-message').hide();
                        $('#rekomendasi-table').show();

                        // Handle pagination links
                        $('#pagination-links').html(response.rekomendasi.links);
                    } else {
                        // Display the error message
                        $('#rekomendasi-message').text('Maaf sepertinya tidak ada prodi yang cocok untuk kamu').show();
                        $('#rekomendasi-table').hide();
                        $('#pagination-links').empty();
                    }
                }
            },
            error: function(error) {
                var errorMessage = error.responseJSON.error;
                $('#rekomendasi-message').text(errorMessage).show();
            }
        });
    });
});


</script>

@endsection   
