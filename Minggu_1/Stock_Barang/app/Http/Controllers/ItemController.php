<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nama_Barang' => 'required',
            'Jumlah' => 'required|integer',
            'Deskripsi' => 'required',
        ]);

        // Hanya masukkan atribut yang diizinkan
        Item::create($request->only(['Nama_Barang', 'Jumlah', 'Deskripsi']));
        return redirect()->route('items.index')->with('success', 'Item added successfully.');
    }

    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'Nama_Barang' => 'required',
            'Jumlah' => 'required|integer',
            'Deskripsi' => 'required',
        ]);

        // Hanya masukkan atribut yang diizinkan
        $item->update($request->only(['Nama_Barang', 'Jumlah', 'Deskripsi']));
        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
