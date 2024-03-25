@extends('layouts.main')

@section('title')
<h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">TRYOUT SAYA</h6>
@endsection

@section('content')
<div class="table">
    <div class="card">
        <div class="card-body">
            <table class="table table-striped mt-5">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Tryout</th>
                        <th>Tanggal</th>
                        <th>Rata-Rata Nilai Tryout</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tryouts as $index => $tryout)
                    <?php
                        $avg = ($bobot_ppu * $tryout->ppu) + ($bobot_pu * $tryout->pu) + ($bobot_pm * $tryout->pm) + ($bobot_pk * $tryout->pk) + ($bobot_lbi * $tryout->lbi) + ($bobot_lbe * $tryout->lbe) + ($bobot_pbm * $tryout->pbm);
                        $avg = $avg / 7;
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
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
