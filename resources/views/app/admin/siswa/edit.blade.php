@extends('layouts_admin.main')

@section('title')
        <h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">UBAH NILAI SISWA</h6>
@endsection

@section('content')
<style>
    .row .col-xl-6 {
        font-size: 15px;
    }
</style>
<div class="container">
    <div class="row m-3">
        <div class="col-xl-6">
            <h4>{{ $siswa->first_name }}</h4>
            <p>{{$siswa->asal_sekolah}}</p>
        </div>
        <div class="col-xl-6">
        <form action="{{ route('admin.siswa.tryout.update', ['username' => $siswa->username, 'nama_tryout' => $tryout->nama_tryout]) }}" method="POST">
            @csrf
            <input type="hidden" name="nama_tryout" value="{{ $tryout->nama_tryout }}">
            <p>Tryout ke : {{ $tryout->nama_tryout }}</p>
            <p>Tanggal Tryout : <input type="date" name="tanggal" value="{{ $tryout->tanggal }}" required style="width: 200px; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; color: #495057; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; border-radius: .25rem; transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;"></p>
        </div>
        <hr>
    </div>
    <div class="row justify-content-center text-center">
        <div class="col-xl-3">
            <div class="card" style="background-color: #E0F1FA;">
                <div class="d-flex text-center justify-content-center">
                    <h5 class="text-dark m-2">Nilai Siswa</h5>
                    @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                </div>
            </div>
        </div>      
    </div>
        <div class="row justify-content-center mt-4 mr-3 ml-3">
            <div class="col-xl-6">
                <h4>PPU</h4>
                <input type="number" class="form-control mb-2" name="ppu" value="{{ $tryout->ppu }}" required>

                <h4>PU</h4>
                <input type="number" class="form-control mb-2" name="pu" value="{{ $tryout->pu }}" required>

                <h4>PM</h4>
                <input type="number" class="form-control mb-2" name="pm" value="{{ $tryout->pm }}" required>

                <h4>PK</h4>
                <input type="number" class="form-control mb-2" name="pk" value="{{ $tryout->pk }}" required>
            </div>
            <div class="col-xl-6">
                <h4>LBI</h4>
                <input type="number" class="form-control mb-2" name="lbi" value="{{ $tryout->lbi }}" required>

                <h4>LBE</h4>
                <input type="number" class="form-control mb-2" name="lbe" value="{{ $tryout->lbe }}" required>

                <h4>PBM</h4>
                <input type="number" class="form-control mb-2" name="pbm" value="{{ $tryout->pbm }}" required>
            </div>
            
            <div class="justify-content-center text-center mt-3 mb-4">
                <button class="btn" type="submit" style="background-color: #699BF7; color:#fff; width:200px;"">SIMPAN DATA</button>
            </div>
        </div>
    </form>
</div>
@endsection
