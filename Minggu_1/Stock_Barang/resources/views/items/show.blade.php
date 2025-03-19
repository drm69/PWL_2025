<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Barang</title>
</head>
<body>
    <h1>Barang</h1>
    @if (session('success'))
        <p>{{session('success')}}</p>
    @endif

    <a href="{{route('items.create')}}">Tambahkan Barang</a>

    <ul>
        @foreach ($item as $items)
            <li>
                {{$item->name}} -
                <a href="{{ route('items.edit', $item)}}">Edit</a>
                <form action="{{ route('items.destroy', $item)}}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
</body>
</html>