@extends('layouts.main')

@section('title')
<h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">TRYOUT SAYA</h6>
@endsection

@section('content')
<div class="table-responsive">
    <table class="table table-striped mt-5">
        <thead>
            <tr>
                <th>No</th>
                <th>Jumlah Tryout</th>
                <th>Tanggal</th>
                <th>Rata rata Nilai Tryout</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Eugene</td>
                <td>Kopyov</td>
                <td>@Kopyov</td>
                <td><a href="" class="btn btn-primary">Detail</a></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Victoria</td>
                <td>Baker</td>
                <td>@Vicky</td>
                <td><a href="" class="btn btn-primary">Detail</a></td>
            </tr>
            <tr>
                <td>3</td>
                <td>James</td>
                <td>Alexander</td>
                <td>@Alex</td>
                <td><a href="" class="btn btn-primary">Detail</a></td>
            </tr>
            <tr>
                <td>4</td>
                <td>Franklin</td>
                <td>Morrison</td>
                <td>@Frank</td>
                <td><a href="" class="btn btn-primary">Detail</a></td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
