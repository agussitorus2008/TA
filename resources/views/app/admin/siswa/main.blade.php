@extends('layouts_admin.main')

@section('title')
    <h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">DATA SISWA</h6>
@endsection

@section('content')
    <div class="container mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <select name="active" id="active" class="form-control">
                            <option value="{{ $tahunSekarang }}">Pilih Tahun</option>
                            @foreach ($tahun as $data)
                                <option value="{{ $data }}">{{ $data }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <input type="text" id="searchInput" placeholder="Cari siswa..." class="form-control" style="width: 200px;">
                    </div>
                </div>
                <div class="d-flex justify-content-end mb-3">
                    <div class="table">
                        <table id="siswaTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Asal Sekolah</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $currentPage = $siswaList->currentPage();
                                    $perPage = $siswaList->perPage();
                                @endphp
                                @foreach ($siswaList as $index => $siswa)
                                    <tr>
                                        <td>{{ ($currentPage - 1) * $perPage + $loop->iteration }}</td>
                                        <td>{{ $siswa->first_name }}</td>
                                        <td>{{ $siswa->sekolah_siswa->sekolah }}</td>
                                        <td class="{{ $statusList[$siswa->username] == 'Belum Ada Nilai Tryout' ? 'text-danger' : 'text-success' }}">{{ $statusList[$siswa->username] }}</td>
                                        <td>
                                            <a href="{{ route('admin.siswa.tryout', ['username' => $siswa->username]) }}" class="btn btn-primary">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3 d-flex justify-content-center pagination">
                            {{-- {{ $siswaList->links('pagination::bootstrap-4') }} --}}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

<script>
        $(document).ready(function() {
    $('#searchInput').on('keyup', function() {
        var query = $(this).val();
        var active = $('#active').val();
        $.ajax({
            url: '/siswa/search',
            method: 'GET',
            data: { query: query, active:active },
            success: function(response) {
                // Clear the table body
                $('#siswaTable tbody').empty();
                
                // Iterate over the received data and append rows to the table
                $.each(response.siswaList.data, function(index, siswa) {
                    var statusClass = siswa.status == 'Belum Ada Nilai Tryout' ? 'text-danger' : 'text-success';                    var row = '<tr>' +
                        '<td>' + (index + 1) + '</td>' +
                        '<td>' + siswa.first_name + '</td>' +
                        '<td>' + siswa.sekolah_siswa.sekolah + '</td>' + // Pastikan ini sesuai dengan relasi yang telah didefinisikan
                        '<td class="' + statusClass + '">' + siswa.status + '</td>' +
                        '<td><a href="/admin/siswa/tryout/' + siswa.username + '" class="btn btn-primary">Detail</a></td>' +
                        '</tr>';
                    $('#siswaTable tbody').append(row);
                });
                
                // Update pagination links
                $('.pagination').html(response.siswaList.links);
                
                // Add event listener for pagination links
                $('.pagination a').on('click', function(e) {
                    e.preventDefault();
                    var pageUrl = $(this).attr('href');
                    loadPage(pageUrl);
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    // Function to load page content using AJAX
    function loadPage(pageUrl) {
        $.ajax({
            url: pageUrl,
            method: 'GET',
            success: function(response) {
                $('#siswaTable tbody').html(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
});
</script>

<script>
    $(document).ready(function() {
        $('#active').on('change', function() {
            var activeYear = $(this).val();
            fetchSiswaData(activeYear);
        });
    
        function fetchSiswaData(activeYear) {
            $.ajax({
                url: '/siswa/filter',
                method: 'GET',
                data: { active: activeYear },
                success: function(response) {
                    console.log(response); // Log the entire response object
    
                    if (response && response.siswaList && response.siswaList.data) {
                        updateTable(response.siswaList.data);
                        updatePagination(response.siswaList);
                    } else {
                        console.log('Invalid response format');
                        $('#siswaTable tbody').empty();
                        $('.pagination').empty();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    
        function updateTable(data) {
            var tableBody = $('#siswaTable tbody');
            tableBody.empty();
    
            $.each(data, function(index, siswa) {
                var statusClass = siswa.status == 'Belum Ada Nilai Tryout' ? 'text-danger' : 'text-success';  
                var row = '<tr>' +
                    '<td>' + (index + 1) + '</td>' +
                    '<td>' + siswa.first_name + '</td>' +
                    '<td>' + siswa.sekolah_siswa.sekolah + '</td>' +
                    '<td class="' + statusClass + '">' + siswa.status + '</td>' +
                    '<td><a href="/admin/siswa/tryout/' + siswa.username + '" class="btn btn-primary">Detail</a></td>' +
                    '</tr>';
                tableBody.append(row);
            });
        }
    
        function updatePagination(siswaList) {
            var paginationHtml = '';
    
            if (siswaList.links) {
                paginationHtml += '<nav aria-label="Page navigation"><ul class="pagination">';
                $.each(siswaList.links, function(index, link) {
                    paginationHtml += '<li class="page-item ' + (link.active ? 'active' : '') + '">';
                    paginationHtml += '<a class="page-link" href="' + link.url + '">' + link.label + '</a>';
                    paginationHtml += '</li>';
                });
                paginationHtml += '</ul></nav>';
            }
    
            $('.pagination').html(paginationHtml);
    
            $('.pagination a').on('click', function(e) {
                e.preventDefault();
                var pageUrl = $(this).attr('href');
                fetchPage(pageUrl);
            });
        }
    
        function fetchPage(pageUrl) {
            $.ajax({
                url: pageUrl,
                method: 'GET',
                success: function(response) {
                    if (response && response.data) {
                        updateTable(response.data);
                        updatePagination(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    });
</script>
    
    

    
    
@endsection
