@extends('layouts.main')

@section('title')
<h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">SIMULASI</h6>
@endsection
@section('content')
<div class="content">

    <form action="{{route('siswa.simulasi.test')}}" method="POST">
        @csrf
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>PPU</h5>
                    <input type="number" class="form-control" name="ppu">
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>PM</h5>
                    <input type="number" class="form-control" name="pm">
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>PK</h5>
                    <input type="number" class="form-control" name="pk">
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>LBI</h5>
                    <input type="number" class="form-control" name="lbi">
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>LBE</h5>
                    <input type="number" class="form-control" name="lbe">
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>PBM</h5>
                    <input type="number" class="form-control" name="pbm">
                </div>
            </div>
            <hr>
        </div>
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>Program Studi</h5>
                        <select name="prodi" class="form-control" id="select2">
                            @foreach($prodi as $p)
                            <option value="{{ $p->id_prodi }}">{{ $p->nama_prodi_ptn }}</option>
                            @endforeach
                        </select>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>PTN</h5>
                    <input type="text" name="ptn" id="" class="form-control">
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5></h5>
                    <button class="btn" type="submit" style="background-color: #0A407F; color:#fff">Generate</button>
                </div>
                <hr>
            </div>
        </div>
    </form>

    <p class="text-center">Berikut rekomendasi PTN dan Program Studi yang cocok untuk Anda</p>
    
    <div class="card">
        <table class="table datatable-basic">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Program Studi</th>
                    <th>PTN</th>
                    <th>Rata-Rata</th>
                </tr>
            </thead>
            <tbody>
        
            </tbody>
        </table>
    </div>  
</div>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script>
   $(document).ready(function() {
  $("#select2").select2();
});
</script>
@endsection   
