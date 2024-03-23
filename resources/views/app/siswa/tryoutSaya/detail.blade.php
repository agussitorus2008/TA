@extends('layouts.main')

@section('title')
<h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">DETAIL TRYOUT SAYA</h6>
@endsection

@section('content')
<div class="container  mt-3">
    <div class="card">
        <div class="card-body">
            <h1>Detail Tryout {{ $tryout->nama_tryout }}</h1>
            <div class="table-responsive">
                <table class="table table-striped mt-5">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <?php
                        $nilai_ppu = ($bobot_ppu * $tryout->ppu) * 10;
                        $nilai_pm = ($bobot_pm * $tryout->pm) * 10;
                        $nilai_pk = ($bobot_pk * $tryout->pk) * 10;
                        $nilai_lbi = ($bobot_lbi * $tryout->lbi) * 10;
                        $nilai_lbe = ($bobot_lbe * $tryout->lbe) * 10;
                        $nilai_pbm = ($bobot_pbm * $tryout->pbm) * 10;

                        $nilai_ppu = number_format($nilai_ppu, 2);
                        $nilai_pm = number_format($nilai_pm, 2);
                        $nilai_pk = number_format($nilai_pk, 2);
                        $nilai_lbi = number_format($nilai_lbi, 2);
                        $nilai_lbe = number_format($nilai_lbe, 2);
                        $nilai_pbm = number_format($nilai_pbm, 2);
                    ?>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>PPU</td>
                            <td>{{ $nilai_ppu }}</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>PM</td>
                            <td>{{ $nilai_pm }}</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>PK</td>
                            <td>{{ $nilai_pk }}</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>LBI</td>
                            <td>{{ $nilai_lbi }}</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>LBE</td>
                            <td>{{ $nilai_lbe }}</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>PBM</td>
                            <td>{{ $nilai_pbm }}</td>
                        </tr>
                        <tr>
                            <td colspan=2>RATA-RATA</td>
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
