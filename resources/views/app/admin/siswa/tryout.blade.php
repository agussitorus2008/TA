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
                    <h5>{{ optional($siswa)->first_name }}</h5>
                    <h5>{{ optional($siswa)->asal_sekolah }}</h5>
                </div>
                <div class="d-flex justify-content-end mb-3">
                    <div class="table">
                        <table class="table">
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
                                        <td>{{ $tryout->nama_to }}</td>
                                        <td>{{ $tryout->tanggal }}</td>
                                        <td>{{ $tryout->total }}</td>
                                        <td>
                                            <a href="">Detail Siswa</a>
                                            <button onclick="editTryout({{ $tryout->id_to }})">Ubah</button>
                                            <button onclick="deleteTryout({{ $tryout->id_to }})">Hapus</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
