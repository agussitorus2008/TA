@extends('layouts.main')

@section('title')
<h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">DETAIL TRYOUT SAYA</h6>
@endsection

@section('content')
<div class="container  mt-3">
    <div class="card">
        <div class="card-body">
            <h1>Detail {{ $tryout->nama_to }}</h1>
            <div class="table-responsive">
                <table class="table table-striped mt-5">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis</th>
                            <th>Jumlah Benar</th>
                            <th>Total Soal</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>PPU</td>
                            <td>{{ $tryout->ppu_benar }}</td>
                            <td>{{ $tryout->t_ppu }}</td>
                            <td>{{ number_format($tryout->nilai_ppu, 2) * 10}}</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>PU</td>
                            <td>{{ $tryout->pu_benar }}</td>
                            <td>{{ $tryout->t_pu }}</td>
                            <td>{{ number_format($tryout->nilai_pu, 2) * 10}}</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>PM</td>
                            <td>{{ $tryout->pm_benar }}</td>
                            <td>{{ $tryout->t_pm }}</td>
                            <td>{{ number_format($tryout->nilai_pm, 2) * 10}}</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>PK</td>
                            <td>{{ $tryout->pk_benar }}</td>
                            <td>{{ $tryout->t_pk }}</td>
                            <td>{{ number_format($tryout->nilai_pk, 2) * 10}}</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>LBI</td>
                            <td>{{ $tryout->lbi_benar }}</td>
                            <td>{{ $tryout->t_lbi }}</td>
                            <td>{{ number_format($tryout->nilai_lbi, 2) * 10}}</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>LBE</td>
                            <td>{{ $tryout->lbe_benar }}</td>
                            <td>{{ $tryout->t_lbe }}</td>
                            <td>{{ number_format($tryout->nilai_lbe, 2) * 10}}</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>PBM</td>
                            <td>{{ $tryout->pbm_benar }}</td>
                            <td>{{ $tryout->t_pbm }}</td>
                            <td>{{ number_format($tryout->nilai_pbm, 2) * 10}}</td>
                        </tr>
                        <tr>
                            <td colspan=2>TOTAL</td>
                            <td>{{ $tryout->total_benar }}</td>
                            <td>{{ $tryout->total }}</td>
                            <td>{{ $rata }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="text-align: right;" class="mt-3">
                <a href="{{route('siswa.tryoutSaya.main')}}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
