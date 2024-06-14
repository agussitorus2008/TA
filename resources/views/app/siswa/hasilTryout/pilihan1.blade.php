<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hasil Tryout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body> 
    <div class="container">
        <h1>Hasil Tryout - {{ $nama_prodi }}</h1>
        <p>Anda berada di peringkat ke-{{ $userRank }} dengan total nilai {{ number_format($userTotalNilai * 10, 2) }}.</p>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Peringkat</th>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    {{-- <th>Nama Sekolah</th> --}}
                    <th>Total Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($listNilaipilihan1 as $index => $nilai)
                    <tr @if($nilai['username'] == auth()->user()->email) class="table-success" @endif>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $nilai['username'] }}</td>
                        <td>{{ $nilai['first_name'] }}</td>
                        {{-- <td>{{ $nilai['asal_sekolah'] }}</td> --}}
                        <td>{{ number_format($nilai['average_to'] * 10, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
