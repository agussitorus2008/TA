@extends('layouts_admin.main')

@section('title')
    <h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">DATA SISWA</h6>
@endsection

@section('content')
    <div class="container mt-3">
        <div class="card">
            <div class="card-body">
                <input type="text" id="searchInput" placeholder="Cari siswa..." class="form-control" style="width: 200px;">
                {{-- <select name="active" id="active">
                    @foreach ($tahun as $siswa)
                        <option value="{{ $siswa}}">{{ $siswa}}</option>
                    @endforeach
                </select> --}}
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
                                @foreach ($siswaList as $index => $siswa)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $siswa->first_name }}</td>
                                        <td>{{ $siswa->asal_sekolah }}</td>
                                        <td class="{{ $statusList[$siswa->username] == 'Belum Ada Nilai Tryout' ? 'text-danger' : 'text-success' }}">{{ $statusList[$siswa->username] }}</td>
                                        <td>
                                            <a href="{{ route('admin.siswa.detailtryout', ['username' => $siswa->username]) }}" class="btn btn-primary">Detail</a>
                                        </td>
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

    <script>
        $('#searchInput').on('keyup', function() {
            var query = $(this).val();
    
            $.ajax({
                url: '/siswa/search',
                method: 'GET',
                data: { query: query },
                success: function(response) {
                    // Clear the table body
                    $('#siswaTable tbody').empty();
                    
                    // Iterate over the received data and append rows to the table
                    $.each(response.siswaList.data, function(index, siswa) {
                        var row = '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + siswa.first_name + '</td>' +
                            '<td>' + siswa.sekolah + '</td>' +
                            '<td>' + siswa.status + '</td>' +
                            '<td><a href="/admin/siswa/tryout/' + siswa.username + '" class="btn btn-primary">Detail</a></td>' +
                            '</tr>';
                        $('#siswaTable tbody').append(row);
                    });
                    
                    // Update pagination links
                    // $('.pagination').html(response.siswaList.links);
                    
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
                    url: '/siswa/filter',
                    method: 'GET',
                    data: { active: activeYear },
                    success: function(response) {
                        console.log(response); // Log the entire response object

                        if (response && response.siswaList && response.siswaList.data) {
                            console.log(response.siswaList); // Log the siswaList object

                            // Check if data array is present and not empty
                            if (response.siswaList.data.length > 0) {
                                console.log(response.siswaList.data.length); // Log the length of the data array
                                updateTable(response.siswaList);
                                updatePagination(response.siswaList);
                            } else {
                                console.log('Data array is empty');
                                // Clear the table content if data array is empty
                                $('#siswaTable tbody').empty();
                                // Clear pagination links
                                $('.pagination').empty();
                            }
                        } else {
                            console.log('Invalid response format');
                            // Clear the table content if response format is invalid
                            $('#siswaTable tbody').empty();
                            // Clear pagination links
                            $('.pagination').empty();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
    
            // Function to update table content
            function updateTable(siswaList) {
                var tableBody = $('#siswaTable tbody');
                tableBody.empty(); // Clear previous content
                
                $.each(siswaList.data, function(index, siswa) {
                    var row = '<tr>' +
                        '<td>' + (index + 1) + '</td>' +
                        '<td>' + siswa.first_name + '</td>' +
                        '<td>' + siswa.asal_sekolah + '</td>' +
                        '<td>' + siswa.status + '</td>' +
                        '<td><a href="/admin/siswa/tryout/' + siswa.username + '" class="btn btn-primary">Detail</a></td>' +
                        '</tr>';
                    tableBody.append(row);
                });
            }
    
            // Function to update pagination links
            function updatePagination(siswaList) {
                var pagination = siswaList.links;
    
                if (pagination) {
                    $('.pagination').html(pagination);
                    $('.pagination a').on('click', function(e) {
                        e.preventDefault();
                        var pageUrl = $(this).attr('href');
                        fetchPage(pageUrl);
                    });
                }
            }
    
            // Function to fetch page content using AJAX
            function fetchPage(pageUrl) {
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
    

    
    
@endsection
