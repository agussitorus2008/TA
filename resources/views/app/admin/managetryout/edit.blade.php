@extends('layouts_admin.main')

@section('title')
        <h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">TAMBAH TRYOUT</h6>
@endsection

@section('content')
<style>
    .row .col-xl-6 {
        font-size: 15px;
    }
</style>
<div class="container">
    <form action="{{ route('admin.managetryout.update', ['id' => $tryout->id_to]) }}" method="POST">
        @csrf
        <div class="row m-3">
            <div class="form-row align-items-center">
                <div class="form-group col-md-4">
                    <label for="nama_tryout">Nama Tryout :</label>
                    <input type="text" class="form-control" name="nama_tryout" id="nama_tryout" value="{{ $tryout->nama_to }}">
                </div>
                <div class="form-group col-md-3">
                    <label for="active">Tahun Tryout :</label>
                    <input type="number" class="form-control" name="tahun" id="tahun" value="{{ $tryout->active }}">
                </div>
                <div class="form-group col-md-5">
                    <label for="tanggal">Tanggal Tryout :</label>
                        <input type="date" class="form-control" name="tanggal" id="tanggal" required value="{{ $tryout->tanggal }}">
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
                    <h5 class="text-dark m-2">Jumlah Soal</h5>
                </div>
            </div>
        </div>      
    </div>
        <div class="row justify-content-center mt-4 mr-3 ml-3">
            <div class="col-xl-6">
                <h4>PPU</h4>
                <input type="number" class="form-control mb-2" name="t_ppu" id="t_ppu" value="{{ $tryout->t_ppu }}" required>

                <h4>PU</h4>
                <input type="number" class="form-control mb-2" name="t_pu" id="t_pu" value="{{ $tryout->t_pu }}" required>

                <h4>PM</h4>
                <input type="number" class="form-control mb-2" name="t_pm" id="t_pm" value="{{ $tryout->t_pm }}" required>

                <h4>PK</h4>
                <input type="number" class="form-control mb-2" name="t_pk" id="t_pk" value="{{ $tryout->t_pk }}" required>
            </div>
            <div class="col-xl-6">
                <h4>LBI</h4>
                <input type="number" class="form-control mb-2" name="t_lbi" id="t_lbi" value="{{ $tryout->t_lbi }}" required>

                <h4>LMB</h4>
                <input type="number" class="form-control mb-2" name="t_lbe" id="t_lbe" value="{{ $tryout->t_lbe }}" required>

                <h4>PMB</h4>
                <input type="number" class="form-control mb-2" name="t_pbm" id="t_pbm" value="{{ $tryout->t_pbm }}" required>
            </div>

            <div class="row justify-content-center text-center mt-3 mb-4">
                <div class="col-2">
                    <h4>Total Soal</h4>
                    <input type="number" class="form-control mb-2" name="total" id="total" value="{{ $tryout->total }}" readonly>
                </div>
            </div>            

            <div class="justify-content-center text-center mt-3 mb-4">
                <button class="btn" type="submit" style="background-color: #699BF7; color:#fff; width:200px;"">SIMPAN DATA</button>
            </div>
        </div>
    </form>
</div>

<script>
    // Memastikan dokumen siap untuk dimanipulasi
    $(document).ready(function() {
        // Mengambil elemen-elemen input berdasarkan ID
        var t_pu = $('#t_pu');
        var t_ppu = $('#t_ppu');
        var t_pm = $('#t_pm');
        var t_pk = $('#t_pk');
        var t_lbi = $('#t_lbi');
        var t_lbe = $('#t_lbe');
        var t_pbm = $('#t_pbm');

        // Menambahkan event listener untuk perubahan nilai pada input
        $('input').on('input', function() {
            // Mengambil nilai dari masing-masing input
            var value_t_pu = parseInt(t_pu.val()) || 0;
            var value_t_ppu = parseInt(t_ppu.val()) || 0;
            var value_t_pm = parseInt(t_pm.val()) || 0;
            var value_t_pk = parseInt(t_pk.val()) || 0;
            var value_t_lbi = parseInt(t_lbi.val()) || 0;
            var value_t_lbe = parseInt(t_lbe.val()) || 0;
            var value_t_pbm = parseInt(t_pbm.val()) || 0;

            // Menghitung total
            var total = value_t_pu + value_t_ppu + value_t_pm + value_t_pk + value_t_lbi + value_t_lbe + value_t_pbm;

            // Menetapkan nilai total ke input dengan ID 'total'
            $('#total').val(total);
        });
    });
</script>

@endsection
