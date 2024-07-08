@extends('layouts_admin.main')

@section('title')
        <h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">TAMBAH NILAI SISWA</h6>
@endsection

@section('content')
<style>
    .row .col-xl-6 {
        font-size: 15px;
    }
</style>
<div class="container">
    @if($nama_tryout == null)
            <div class="row justify-content-center text-center mt-3">
                <div class="col-auto">
                    <h4 class="alert alert-danger">Tryout Selanjutnya Belum Tersedia</h4>
                </div>
            </div>
    @else
    <div class="row m-3">
        <div class="col-xl-6">
            <h4>{{ $siswa->first_name }}</h4>
            <p>{{$siswa->sekolah_siswa->sekolah}}</p>
            <p id="active">{{$siswa->active}}</p>
        </div>
        <div class="col-xl-6">
        <form action="{{ route('admin.siswa.tryout.add', ['username' => $siswa->username]) }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="nama_tryout">Nama Tryout :</label>
                    <select name="nama_to" id="nama_to" class="form-control" readonly>
                        @foreach($dataTryout as $data)
                            <option value="{{ $data->nama_to }}" {{ $data->nama_to == $nama_tryout ? 'selected' : '' }}>
                                {{ $data->nama_to }}
                            </option>
                        @endforeach
                    </select>                    
                </div>
                <div class="form-group col-md-8">
                    <label for="tanggal">Tanggal Tryout :</label>
                    <input type="date" class="form-control mb-2" name="tanggal" id="tanggal" required style="width: 200px;" value="{{$tanggal_tryout}}">
                </div>
            </div>
        </div>
        <hr>
    </div>
    <div class="row justify-content-center text-center">
        @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
        @endif
        <div class="col-xl-3">
            <div class="card" style="background-color: #E0F1FA;">
                <div class="d-flex text-center justify-content-center">
                    <h5 class="text-dark m-2">Nilai Siswa</h5>
                </div>
            </div>
        </div>      
    </div>
        <div class="row justify-content-center mt-4 mr-3 ml-3">
            <div class="col-xl-6">
                <h4>PPU</h4>
                <input type="number" class="form-control mb-2" name="ppu" value="{{ old('ppu') }}" required>

                <h4>PU</h4>
                <input type="number" class="form-control mb-2" name="pu" value="{{ old('pu') }}" required>

                <h4>PM</h4>
                <input type="number" class="form-control mb-2" name="pm" value="{{ old('pm') }}" required>

                <h4>PK</h4>
                <input type="number" class="form-control mb-2" name="pk" value="{{ old('pk') }}" required>
            </div>
            <div class="col-xl-6">
                <h4>LBI</h4>
                <input type="number" class="form-control mb-2" name="lbi" value="{{ old('lbi') }}" required>

                <h4>LMB</h4>
                <input type="number" class="form-control mb-2" name="lbe" value="{{ old('lbe') }}" required>

                <h4>PMB</h4>
                <input type="number" class="form-control mb-2" name="pbm" value="{{ old('pbm') }}" required>
            </div>
            
            <div class="justify-content-center text-center mt-3 mb-4">
                <button class="btn" type="submit" style="background-color: #699BF7; color:#fff; width:200px;"">SIMPAN DATA</button>
            </div>
        </div>
    </form>
    @endif
</div>

<script>
    $(document).ready(function() {
        $('#nama_to').on('change', function() {
            var nama_to = $(this).val();
            var active = $('#active').text(); 
            $.ajax({
                url: '/tryout/getTanggal',
                method: 'GET',
                data: { nama_to: nama_to, active: active },
                success: function(response) {
                    try {
                        $('#tanggal').val(response.tanggal);
                    } catch (error) {
                        console.error('Error processing response:', error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
@endsection
