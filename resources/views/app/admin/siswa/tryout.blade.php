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
                    <p style="font-size: 18px">{{ $siswa->asal_sekolah }}</p>
                </div>
                <div style="text-align: right;">
                    <a href="{{ route('admin.siswa.tryout.add', ['username' => $siswa->username]) }}" class="btn btn-primary mb-2">Tambah</a>
                </div>
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif                               
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
                                    <?php
                                        $avg = ($bobot_ppu * $tryout->ppu) + ($bobot_pu * $tryout->pu) + ($bobot_pm * $tryout->pm) + ($bobot_pk * $tryout->pk) + ($bobot_lbi * $tryout->lbi) + ($bobot_lbe * $tryout->lbe) + ($bobot_pbm * $tryout->pbm);
                                        $avg = $avg / 7;
                                        $avg = number_format($avg, 2) * 10;
                                    ?>
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>Tryout {{ $tryout->nama_tryout }}</td>
                                        <td>{{ \Carbon\Carbon::parse($tryout->tanggal)->format('d-m-y') }}</td>
                                        <td>{{ $avg }}</td>
                                        <td>
                                            <a href="{{ route('admin.siswa.tryout.detail', ['username' => $siswa->username, 'nama_tryout' => $tryout->nama_tryout, 'rata' => $avg]) }}" class="btn btn-primary">Detail</a>
                                            <a href="{{ route('admin.siswa.tryout.edit', ['username' => $siswa->username, 'nama_tryout' => $tryout->nama_tryout]) }}" class="btn btn-warning">Ubah</a>
                                            <form action="{{ route('admin.siswa.tryout.delete', ['username' => $siswa->username, 'nama_tryout' => $tryout->nama_tryout]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>                              
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