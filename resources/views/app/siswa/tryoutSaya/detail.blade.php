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
                            <th>Jumlah Benar</th>
                            {{-- <th>Bobot</th> --}}
                            <th>Nilai</th>
                        </tr>
                    </thead>
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
                    <tbody>
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
            </div>
            <div style="text-align: right;" class="mt-3">
                <a href="{{route('siswa.tryoutSaya.main')}}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
