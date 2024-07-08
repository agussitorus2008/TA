@extends('layouts_admin.main')

@section('title')
    <h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">DATA SISWA</h6>
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
                <div class="">
                    <h5>{{ $siswa->first_name }}</h5>
                    <p style="font-size: 18px">{{ $siswa->sekolah_siswa->sekolah }}</p>
                </div>
                @if($nilaiRata == null)
                    <h5 class="alert alert-danger text-center">Belum Ada Data Nilai</h5>
                @else                              
                <div class="d-flex justify-content-end mb-3">
                    <div class="table">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jumlah Try Out</th>
                                    <th>Tanggal</th>
                                    <th>Rata-Rata Tryout</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tryouts as $index => $tryout)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $tryout->tryout->nama_to }}</td>
                                        <td>{{ $tryout->tanggal ? $tryout->tanggal->format('d-m-Y') : 'Tidak Tersedia' }}</td>
                                        <td>{{ number_format($nilai[$index]->total_nilai, 2) * 10 }}</td>
                                        <td>
                                            <a href="{{ route('admin.siswa.detailtryout.detail', ['username' => $siswa->username, 'id_to' => $tryout->id_to, 'rata' => number_format($nilai[$index]->total_nilai, 2) * 10]) }}" class="btn btn-primary">Detail</a>                            
                                        </td>                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <h5>Nilai Rata - Rata : {{ number_format($nilaiRata->average_to, 2) * 10 }}</h5>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection