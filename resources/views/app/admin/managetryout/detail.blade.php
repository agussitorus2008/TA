@extends('layouts_admin.main')

@section('title')
    <h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">DETAIL TRYOUT SISWA</h6>
@endsection

<style>
    .ab h5 {
        margin: 0;
    }
</style>
@section('content')
    <div class="container mt-3">
        <div class="card">
            <div class="card-body">
                <div class="ab">
                    <p>Nama Tryout : {{ $tryout->nama_to }}</p>
                    <p>Tahun : {{ $tryout->active }}</p>
                    <p>Tanggal Tryout :{{ \Carbon\Carbon::parse($tryout->tanggal)->format('d-m-y') }}</p>
                </div>
                <div class="d-flex justify-content-end mb-3">

                    <div class="table">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis</th>
                                    <th>Jumlah Soal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>PPU</td>
                                    <td>{{ $tryout->t_ppu }}</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>PU</td>
                                    <td>{{ $tryout->t_pu }}</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>PM</td>
                                    <td>{{ $tryout->t_pm }}</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>PK</td>
                                    <td>{{ $tryout->t_pk }}</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>LBI</td>
                                    <td>{{ $tryout->t_lbi }}</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>LBE</td>
                                    <td>{{ $tryout->t_lbe }}</td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>PBM</td>
                                    <td>{{ $tryout->t_pbm }}</td>
                                </tr>
                                <tr>
                                    <td colspan=2>Total</td>
                                    <td>{{ $tryout->total }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div style="text-align: right;">
                            <a href="{{route('admin.managetryout.main')}}" class="btn btn-primary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
