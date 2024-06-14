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
                                    <th>Jumlah Benar</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $nilai_ppu = ($tryout->ppu/$bobot_ppu) * 100;
                                    $nilai_pu = ($tryout->pu/$bobot_pu) * 100;
                                    $nilai_pm = ($tryout->pm/$bobot_pm) * 100;
                                    $nilai_pk = ($tryout->pk/$bobot_pk) * 100;
                                    $nilai_lbi = ($tryout->lbi/$bobot_lbi) * 100;
                                    $nilai_lbe = ($tryout->lbe/$bobot_lbe) * 100;
                                    $nilai_pbm = ($tryout->pbm/$bobot_pbm) * 100;

                                    $nilai_ppu = number_format($nilai_ppu * 10, 2);
                                    $nilai_pu = number_format($nilai_pu * 10, 2);
                                    $nilai_pm = number_format($nilai_pm * 10, 2);
                                    $nilai_pk = number_format($nilai_pk * 10, 2);
                                    $nilai_lbi = number_format($nilai_lbi * 10, 2);
                                    $nilai_lbe = number_format($nilai_lbe * 10, 2);
                                    $nilai_pbm = number_format($nilai_pbm * 10, 2);
                                ?>
                                <tr>
                                    <td>1</td>
                                    <td>PPU</td>
                                    <td>{{ $tryout->ppu }}</td>
                                    {{-- <td>{{ $bobot_ppu }}</td> --}}
                                    <td>{{ $nilai_ppu }}</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>PU</td>
                                    <td>{{ $tryout->pu }}</td>
                                    {{-- <td>{{ $bobot_pu }}</td> --}}
                                    <td>{{ $nilai_pu }}</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>PM</td>
                                    <td>{{ $tryout->pm }}</td>
                                    {{-- <td>{{ $bobot_pm }}</td> --}}
                                    <td>{{ $nilai_pm }}</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>PK</td>
                                    <td>{{ $tryout->pk }}</td>
                                    {{-- <td>{{ $bobot_pk }}</td> --}}
                                    <td>{{ $nilai_pk }}</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>LBI</td>
                                    <td>{{ $tryout->lbi }}</td>
                                    {{-- <td>{{ $bobot_lbi }}</td> --}}
                                    <td>{{ $nilai_lbi }}</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>LBE</td>
                                    <td>{{ $tryout->lbe }}</td>
                                    {{-- <td>{{ $bobot_lbe }}</td> --}}
                                    <td>{{ $nilai_lbe }}</td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>PBM</td>
                                    <td>{{ $tryout->pbm }}</td>
                                    {{-- <td>{{ $bobot_pbm }}</td> --}}
                                    <td>{{ $nilai_pbm }}</td>
                                </tr>
                                <tr>
                                    <td colspan=3>RATA-RATA</td>
                                    <td>{{ $rata }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div style="text-align: right;">
                            <a href="{{route('admin.siswa.tryout', $siswa->username)}}" class="btn btn-primary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
