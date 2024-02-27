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
                    <input type="number" class="form-control" name="ppu" required>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>PM</h5>
                    <input type="number" class="form-control" name="pm" required>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>PK</h5>
                    <input type="number" class="form-control" name="pk" required>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>LBI</h5>
                    <input type="number" class="form-control" name="lbi" required>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>LBE</h5>
                    <input type="number" class="form-control" name="lbe" required>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                    <h5>PBM</h5>
                    <input type="number" class="form-control" name="pbm" required>
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
                {{-- @foreach($rekomendasi as $r)
                <tr>
                    <td>{{ $r-> }}</td>
                </tr>
                @endforeach --}}
            </thead>
            <tbody>
        
            </tbody>
        </table>
    </div>  
</div>

<script>
   $(document).ready(function() {
    $("#select2").select2();
    });
</script>
@endsection   
