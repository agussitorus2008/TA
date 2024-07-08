@extends('layouts_admin.main')

@section('title')
    <h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">DATA TRYOUT</h6>
@endsection

@section('content')
    <div class="container mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif   
                    <div class="col-auto">
                        <select name="active" id="active" class="form-control">
                            <option value="{{ $tahunSekarang }}">Pilih Tahun</option>
                            @foreach ($tahun as $tryout)
                                <option value="{{ $tryout->active }}">{{ $tryout->active }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <input type="text" id="searchInput" placeholder="Cari tryout..." class="form-control" style="width: 200px;">
                    </div>
                    <div class="col-auto text-right">
                        <a href="{{ route('admin.managetryout.add')}}" class="btn btn-primary" id="add">Tambah</a>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mb-3">
                    <div class="table">
                        <table id="siswaTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Tryout</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Active</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $currentPage = $tryoutlist->currentPage();
                                    $perPage = $tryoutlist->perPage();
                                @endphp
                                @foreach ($tryoutlist as $index => $tryout)
                                    <tr>
                                        <td>{{ ($currentPage - 1) * $perPage + $loop->iteration }}</td>
                                        <td>{{ $tryout->nama_to }}</td>
                                        <td>{{ $tryout->tanggal }}</td>
                                        <td>{{ $tryout->total }}</td>
                                        <td>{{ $tryout->active }}</td>
                                        <td>
                                            <a href="{{ route('admin.managetryout.detail', ['id' => $tryout->id_to]) }}" class="btn btn-primary">Detail</a>

                                            @if($statusList[$tryout->id_to] != true)
                                            <a href="{{ route('admin.managetryout.edit', ['id' => $tryout->id_to]) }}" class="btn btn-warning">Edit</a>
                                            <form action="{{ route('admin.managetryout.delete', ['id' => $tryout->id_to]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form> 
                                            @endif                                           
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $tryoutlist->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#active').on('change', function() {
                var currentYear = new Date().getFullYear();
                var currentMonth = new Date().getMonth() + 1;
                var checkYear = currentMonth >= 6 ? currentYear + 1 : currentYear;
    
                if ($('#active').val() != checkYear) {
                    $('#add').addClass('d-none');
                } else {
                    $('#add').removeClass('d-none');
                }
            });
        });
    </script>
    
    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var query = $(this).val();
                var active = $('#active').val();
        
                $.ajax({
                    url: '/tryout/search',
                    method: 'GET',
                    data: { query: query, active: active },
                    success: function(response) {
                        try {
                            // Clear the table body
                            $('#siswaTable tbody').empty();
    
                            // Iterate over the received data and append rows to the table
                            $.each(response.siswaList.data, function(index, siswa) {
                                var row = '<tr>' +
                                    '<td>' + (index + 1) + '</td>' +
                                    '<td>' + siswa.nama_to + '</td>' +
                                    '<td>' + siswa.tanggal + '</td>' +
                                    '<td>' + siswa.total + '</td>' +
                                    '<td>' + siswa.active + '</td>' +
                                    '<td><a href="/admin/siswa/managetryout/detail/' + siswa.id_to + '" class="btn btn-primary">Detail</a></td>';
    
                                if (!siswa.status) {
                                    row += '<td><a href="/admin/siswa/managetryout/edit/' + siswa.id_to + '" class="btn btn-warning">Edit</a></td>' +
                                        '<td>' +
                                        '<form action="/admin/siswa/managetryout/delete/' + siswa.id_to + '" method="POST">' +
                                        '<input type="hidden" name="_token" value="' + $('meta[name="csrf-token"]').attr('content') + '">' +
                                        '<input type="hidden" name="_method" value="DELETE">' +
                                        '<button type="submit" class="btn btn-danger">Hapus</button>' +
                                        '</form>' +
                                        '</td>';
                                }
                                row += '</tr>';
    
                                $('#siswaTable tbody').append(row);
                            });
    
                            $('.pagination a').on('click', function(e) {
                                e.preventDefault();
                                var pageUrl = $(this).attr('href');
                                loadPage(pageUrl);
                            });
                        } catch (error) {
                            console.error('Error processing response:', error);
                        }
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
                        try {
                            $('#siswaTable tbody').html(response);
                        } catch (error) {
                            console.error('Error loading page content:', error);
                        }
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

        // Function to fetch siswa data based on active year
        function fetchSiswaData(activeYear) {
            $.ajax({
                url: '/tryout/filter',
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

        // Function to update table content
        function updateTable(data) {
            try {
                var tableBody = $('#siswaTable tbody');
                tableBody.empty(); // Clear previous content 
                $.each(data, function(index, siswa) {
                    var row = '<tr>' +
                                    '<td>' + (index + 1) + '</td>' +
                                    '<td>' + siswa.nama_to + '</td>' +
                                    '<td>' + siswa.tanggal + '</td>' +
                                    '<td>' + siswa.total + '</td>' +
                                    '<td>' + siswa.active + '</td>' +
                                    '<td><a href="/admin/siswa/managetryout/detail/' + siswa.id_to + '" class="btn btn-primary">Detail</a></td>';
    
                                if (!siswa.status) {
                                    row += '<td><a href="/admin/siswa/managetryout/edit/' + siswa.id_to + '" class="btn btn-warning">Edit</a></td>' +
                                        '<td>' +
                                        '<form action="/admin/siswa/managetryout/delete/' + siswa.id_to + '" method="POST">' +
                                        '<input type="hidden" name="_token" value="' + $('meta[name="csrf-token"]').attr('content') + '">' +
                                        '<input type="hidden" name="_method" value="DELETE">' +
                                        '<button type="submit" class="btn btn-danger">Hapus</button>' +
                                        '</form>' +
                                        '</td>';
                                }
                                row += '</tr>';

                    tableBody.append(row);
                });
            } catch (error) {
                console.error('Error updating table:', error);
            }
        }

        // Function to update pagination links
        function updatePagination(siswaList) {
            try {
                var pagination = siswaList.links;

                if (pagination) {
                    var paginationHtml = '';
                    $.each(pagination, function(index, link) {
                        paginationHtml += '<li class="page-item ' + (link.active ? 'active' : '') + '">';
                        paginationHtml += '<a class="page-link" href="' + link.url + '">' + link.label + '</a>';
                        paginationHtml += '</li>';
                    });

                    $('.pagination').html(paginationHtml);

                    $('.pagination a').on('click', function(e) {
                        e.preventDefault();
                        var pageUrl = $(this).attr('href');
                        fetchPage(pageUrl);
                    });
                }
            } catch (error) {
                console.error('Error updating pagination:', error);
            }
        }

        // Function to fetch page content using AJAX
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
