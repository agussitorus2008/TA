@extends('layouts.main')

@section('title')
<h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">SIMULASI PTN</h6>
@endsection
@section('content')
<style>
    #loading-spinner {
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(255, 255, 255, 0.8); /* White background with opacity */
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .spinner-border-lg {
            width: 4rem;
            height: 4rem;
            border-width: 0.5rem;
        }
</style>

<div class="content">
    <div id="loading-spinner" class="d-none">
        <div class="spinner-border spinner-border-lg text-primary" role="status">
        </div>
    </div>

    <form action="{{route('siswa.simulasi.test_ptn')}}" method="POST">
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
                    <button class="btn" type="submit" style="background-color: #0A407F; color:#fff">Simulasi</button>
                </div>
                <hr class="mt-4">
            </div>
        </div>
        @endif
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


{{-- <script>
    $(document).ready(function() {
        $('form').submit(function(event) {
            event.preventDefault();
            $('#loading-spinner').removeClass('d-none');
    
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    $('#loading-spinner').addClass('d-none');
                    if (response.rekomendasi) {
                        const rekomendasiData = response.rekomendasi;
                        if (rekomendasiData.length > 0) {
                            const tbody = $('#rekomendasi-table tbody');
                            tbody.empty();
                            $.each(rekomendasiData, function(index, item) {
                                tbody.append(
                                    '<tr><td>' + (index + 1) + '</td><td>' + item.nama_prodi + '</td><td>' + item.nama_singkat + '</td><td>' + (item.average_total_nilai * 10).toLocaleString('en-US', { minimumFractionDigits: 2 }) + '</td></tr>'
                                );
                            });
                            $('#rekomendasi-message').hide();
                            $('#rekomendasi-table').show();
    
                            // Handle pagination links
                            $('#pagination-links').html(response.rekomendasi.links);
                        } else {
                            displayErrorMessage('Maaf sepertinya tidak ada prodi yang cocok untuk kamu 1');
                        }
                    } else {
                        displayErrorMessage('Maaf sepertinya tidak ada prodi yang cocok untuk kamu 2');
                    }
                },
                error: function(error) {
                    var errorMessage = error.responseJSON ? error.responseJSON.error : 'An error occurred';
                    displayErrorMessage(errorMessage);
                    $('#loading-spinner').addClass('d-none');
                }
            });
        });
    
        function displayErrorMessage(message) {
            $('#rekomendasi-message').text(message).show();
            $('#rekomendasi-table').hide();
            $('#pagination-links').empty();
        }
    });
</script> --}}

<script>
    $(document).ready(function() {
        $('form').submit(function(event) {
            event.preventDefault();
            $('#loading-spinner').removeClass('d-none');

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    $('#loading-spinner').addClass('d-none');
                    if (response.success) {
                        checkRecommendation();
                    } else {
                        displayErrorMessage('Failed to dispatch job.');
                    }
                },
                error: function(error) {
                    var errorMessage = error.responseJSON ? error.responseJSON.error : 'An error occurred';
                    displayErrorMessage(errorMessage);
                    $('#loading-spinner').addClass('d-none');
                }
            });
        });

        function checkRecommendation() {
            $.ajax({
                type: 'GET',
                url: '/siswa/simulasi/get-rekomendasi',
                dataType: 'json',
                success: function(response) {
                    if (response.rekomendasi) {
                        const rekomendasiData = response.rekomendasi;
                        if (rekomendasiData.error) {
                            displayErrorMessage(rekomendasiData.error);
                        } else 
                        if (rekomendasiData.length > 0) {
                            const tbody = $('#rekomendasi-table tbody');
                            tbody.empty();
                            $.each(rekomendasiData, function(index, item) {
                                tbody.append(
                                    '<tr><td>' + (index + 1) + '</td><td>' + item.nama_prodi + '</td><td>' + item.nama_singkat + '</td><td>' + (item.average_total_nilai * 10).toLocaleString('en-US', { minimumFractionDigits: 2 }) + '</td></tr>'
                                );
                            });
                            $('#rekomendasi-message').hide();
                            $('#rekomendasi-table').show();

                        } else {
                            displayErrorMessage('Maaf sepertinya tidak ada prodi yang cocok untuk kamu');
                        }
                    } else {
                        displayErrorMessage('Maaf sepertinya tidak ada prodi yang cocok untuk kamu');
                    }
                },
                error: function(error) {
                    setTimeout(checkRecommendation, 5000); // Retry after 5 seconds
                }
            });
        }

        function displayErrorMessage(message) {
            $('#rekomendasi-message').addClass('alert alert-danger').text(message).show();
            $('#rekomendasi-table').hide();
        }
    });
</script>
    

@endsection   
