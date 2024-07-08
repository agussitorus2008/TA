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
                <div style="text-align: right;">
                    <a href="{{ route('admin.siswa.tryout.add', ['username' => $siswa->username]) }}" class="btn btn-primary mb-2">Tambah</a>
                </div>
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif   
                @if($tryouts == null)
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
                                @foreach ($tryouts as $index => $data)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $data->nama_to }}</td>
                                        <td>{{ $data->tanggal ? $data->tanggal->format('d-m-Y') : 'Tidak Tersedia' }}</td>
                                        <td>{{ number_format($nilai[$index]->total_nilai, 2) * 10 }}</td> <!-- Sesuaikan total_nilai dengan kolom yang ingin ditampilkan -->
                                        <td>
                                            <a href="{{ route('admin.siswa.tryout.detail', ['username' => $siswa->username, 'id_to' => $data->id_to, 'rata' => number_format($nilai[$index]->total_nilai, 2) * 10]) }}" class="btn btn-primary">Detail</a>
                                            <a href="{{ route('admin.siswa.tryout.edit', ['username' => $siswa->username, 'id_to' => $data->id_to]) }}" class="btn btn-warning">Ubah</a>
                                            <form action="{{ route('admin.siswa.tryout.delete', ['username' => $siswa->username, 'id_to' => $data->id_to]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            
                        </table>
                        <h5>Nilai Rata - Rata: {{ $nilaiRata ? number_format($nilaiRata->average_to * 10, 2) : 0 }}</h5>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection