@extends('layouts.main')

@section('title')
<h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">TRYOUT SAYA</h6>
@endsection

@section('content')

<div class="content">
    @if($tryouts == null || $nilaiRata == 0)
        <div class="row justify-content-center text-center">
            <h5 class="text-danger alert alert-danger">Belum ada data Nilai</h5>
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
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $tryout->nama_to }}</td>
                                <td>{{ \Carbon\Carbon::parse($tryout->tanggal)->format('d-m-y') }}</td>
                                <td>{{ number_format($tryout->total_nilai, 2) * 10 }}</td>
                                <td><a href="{{route('siswa.tryoutSaya.detail', ['nama_tryout' => $tryout->id_to, 'rata' => number_format($tryout->total_nilai, 2) * 10])}}" class="btn btn-primary">Detail</a></td>
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
</div>
@endif

@endsection
