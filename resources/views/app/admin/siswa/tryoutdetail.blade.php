@extends('layouts.main')

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
                <div class="ab">
                    <h6>{{ $siswa->first_name }}</h6>
                    <p>{{ $siswa->asal_sekolah }}</p>
                    <p>{{ $tryout->nama_to }}</p>
                    <p>{{ $tryout->tanggal }}</p>
                </div>
                <div class="d-flex justify-content-end mb-3">

                    <div class="table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nilaiTos as $index => $nilaiTo)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $nilaiTo->jenis }}</td>
                                        <td>{{ $nilaiTo->nilai }}</td>
                                    </tr>
                                @endforeach
                                <!-- Tambahkan baris-baris data lainnya sesuai kebutuhan -->
                            </tbody>
                        </table>
                        <div>
                            <a href="{{ route('admin.siswa.tryout') }}" class="btn btn-primary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
