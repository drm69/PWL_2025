<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
</head>
<body>
    <h1>Edit Barang</h1>
    <form action="{{ route('items.update', $item)}}" method="POST">
        @csrf
        @method('PUT')
        <label for="Nama_Barang">Nama Barang</label>
        <input type="text" name="Nama Barang" required id="">
        <br>
        <label for="Jumlah">Jumlah</label>
        <input type="number" name="Jumlah" required id="">
        <br>
        <label for="Deskripsi">Deskripsi</label>
        <textarea name="Deskripsi" required id=""></textarea>
        <br>
        <button type="submit">Update Barang</button>
    </form>
    <a href="{{ route('items.index')}}">Kembali ke Daftar Barang</a>
</body>
</html>