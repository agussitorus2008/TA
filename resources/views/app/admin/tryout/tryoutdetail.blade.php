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
                    <h6>Nama Siswa :{{ $siswa->first_name }}</h6>
                    <p>Asal sekolah :{{ $siswa->asal_sekolah }}</p>
                    <p>Nama Tryout :{{ $tryout->nama_tryout }}</p>
                    <p>Tanggal Tryout :{{ \Carbon\Carbon::parse($tryout->tanggal)->format('d-m-y') }}</p>
                </div>
                <div class="d-flex justify-content-end mb-3">

                    <div class="table">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $nilai_ppu = ($bobot_ppu * $tryout->ppu) * 10;
                                    $nilai_pu = ($bobot_pu * $tryout->pu) * 10;
                                    $nilai_pm = ($bobot_pm * $tryout->pm) * 10;
                                    $nilai_pk = ($bobot_pk * $tryout->pk) * 10;
                                    $nilai_lbi = ($bobot_lbi * $tryout->lbi) * 10;
                                    $nilai_lbe = ($bobot_lbe * $tryout->lbe) * 10;
                                    $nilai_pbm = ($bobot_pbm * $tryout->pbm) * 10;

                                    $nilai_ppu = number_format($nilai_ppu, 2);
                                    $nilai_pu = number_format($nilai_pu, 2);
                                    $nilai_pm = number_format($nilai_pm, 2);
                                    $nilai_pk = number_format($nilai_pk, 2);
                                    $nilai_lbi = number_format($nilai_lbi, 2);
                                    $nilai_lbe = number_format($nilai_lbe, 2);
                                    $nilai_pbm = number_format($nilai_pbm, 2);
                                ?>
                                <tr>
                                    <td>1</td>
                                    <td>PPU</td>
                                    <td>{{ $nilai_ppu }}</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>PU</td>
                                    <td>{{ $nilai_pu }}</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>PM</td>
                                    <td>{{ $nilai_pm }}</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>PK</td>
                                    <td>{{ $nilai_pk }}</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>LBI</td>
                                    <td>{{ $nilai_lbi }}</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>LBE</td>
                                    <td>{{ $nilai_lbe }}</td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>PBM</td>
                                    <td>{{ $nilai_pbm }}</td>
                                </tr>
                                <tr>
                                    <td colspan=2>RATA-RATA</td>
                                    <td>{{ $rata }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div style="text-align: right;">
                            <a href="{{route('admin.siswa.detailtryout', $siswa->username)}}" class="btn btn-primary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
