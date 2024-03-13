@extends('layouts.main')

@section('title')
    <h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">DATA SISWA</h6>
@endsection

@section('content')
    <div class="container mt-3">
        <div class="card">
            <div class="card-body">
                <input type="text" id="searchInput" placeholder="Cari siswa...">
                <button onclick="searchSiswa()">Cari</button>
                <div class="d-flex justify-content-end mb-3">
                    <div class="table">
                        <table id="siswaTable" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Asal Sekolah</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswaList as $index => $siswa)
                                    <tr>
                                        <td>{{ $siswaList->firstItem() + $index }}</td>
                                        <td>{{ $siswa->first_name }}</td>
                                        <td>{{ $siswa->asal_sekolah }}</td>
                                        <td><a href="#" class="detail-link" data-id="{{ $siswa->id }}">Detail</a>
                                        </td>
                                        </td>
                                        <td>{{ $siswa->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $siswaList->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detailLinks = document.querySelectorAll('.detail-link');

            detailLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const siswaId = this.getAttribute('data-id');
                    const url = "{{ route('admin.siswa.tryout.show', ':id') }}";
                    window.location.href = url.replace(':id', siswaId);
                });
            });
        });
    </script>
@endsection
