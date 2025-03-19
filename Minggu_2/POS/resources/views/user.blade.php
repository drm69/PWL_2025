<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
</head>
<body>
    <h1>Halo, {{ $name }} (ID: {{ $id }})</h1>
    <h2>Pilih Menu</h2>
    <a href="{{route('product')}}">Lihat Produk</a>
</body>
</html>