@extends('layouts.main')

@section('title')
<h6 style="color:#0A407F;font-size:25px" class="d-none d-sm-inline-block h-16px ms-3">DETAIL TRYOUT SAYA</h6>
@endsection

@section('content')
<h1>Detail Tryout 1</h1>
<div class="table-responsive">
    <table class="table table-striped mt-5">
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Eugene</td>
                <td>Kopyov</td>
                <td>@Kopyov</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Victoria</td>
                <td>Baker</td>
                <td>@Vicky</td>
            </tr>
            <tr>
                <td>3</td>
                <td>James</td>
                <td>Alexander</td>
                <td>@Alex</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Franklin</td>
                <td>Morrison</td>
                <td>@Frank</td>
            </tr>
        </tbody>
        <tr>
            <td colspan=2>Rata Rata</td>
            <td>80</td>
        </tr>
    </table>
</div>
<a href="" class="btn btn-primary">Kembali</a>
@endsection
