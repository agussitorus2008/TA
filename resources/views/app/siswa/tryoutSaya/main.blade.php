@extends('layouts.main')

@section('title')
<h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">TRYOUT SAYA</h6>
@endsection

@section('content')

@if($tryouts == null)
    <div class="row justify-content-center">
        <h5 class="text-danger">Belum ada data nilai</h5>
    </div>
@else
<div class="table">
    <div class="card">
        <div class="card-body">
            <table class="table table-striped mt-5">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Tryout</th>
                        <th>Tanggal</th>
                        <th>Nilai Tryout</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tryouts as $index => $tryout)
                    <?php
                        $avg = ($tryout->ppu/$bobot_ppu * 100) + ($tryout->pu/$bobot_pu * 100) + ($tryout->pm/$bobot_pm*100) + ($tryout->pk/$bobot_pk*100) + ($tryout->lbi/$bobot_lbi*100) + ($tryout->lbe/$bobot_lbe*100) + ($tryout->pbm/$bobot_pbm*100);
                        $avg = ($tryout->pu + $tryout->ppu + $tryout->pm + $tryout->pk + $tryout->lbi + $tryout->lbe + $tryout->pbm)/$bobot_total*100;
                        $avg = number_format($avg, 2) * 10;
                    ?>
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>Tryout {{ $tryout->nama_tryout }}</td>
                        <td>{{ \Carbon\Carbon::parse($tryout->tanggal)->format('d-m-y') }}</td>
                        <td>{{ $avg }}</td>
                        <td><a href="{{route('siswa.tryoutSaya.detail', ['nama_tryout' => $tryout->nama_tryout, 'rata' => $avg])}}" class="btn btn-primary">Detail</a></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="4"><p style="font-weight:bold; font-size:20px;">Nilai Rata - Rata</p></td>
                        <td><p style="font-weight:bold; font-size:20px;">{{ number_format($nilaiRata, 2) * 10 }}</p></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection
