<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data User</title>
</head>
<body>
    <h1>Data User</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Nama</th>
            <th>ID Level Pengguna</th>
            <th>Kode Level</th>
            <th>Nama Level</th>
            <th>Aksi</th>
        </tr>
        @foreach ($data as $d)
        <tr>
            <td>{{$d->user_id}}</td>
            <td>{{$d->username}}</td>
            <td>{{$d->nama}}</td>
            <td>{{$d->level_id}}</td>
            <td>{{$d->level->level_kode}}</td>
            <td>{{$d->level->level_nama}}</td>
            <td><a href="{{route('ubah', $d)}}">Ubah</a> | <a href="{{route('hapus', $d)}}">Hapus</a></td>
        </tr>
        @endforeach
    </table>
    <a href="{{route('tambah')}}">tambah user</a>
</body>
</html>