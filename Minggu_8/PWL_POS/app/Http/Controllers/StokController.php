<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\StokModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StokController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) ['title' => 'Stok Barang', 'list' => ['Home', 'Stok']];
        $page = (object) ['title' => 'Stok Barang'];
        $activeMenu = 'stok';
        $kategori = KategoriModel::all();

        return view('stok.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $stoks = StokModel::with('barang.kategori')
            ->select('stok_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah');

        // Filter berdasarkan kategori
        if ($request->kategori_id) {
            $stoks->whereHas('barang', function ($query) use ($request) {
                $query->where('kategori_id', $request->kategori_id);
            });
        }

        // Search berdasarkan nama barang atau nama kategori
        if ($request->search['value']) {
            $search = $request->search['value'];
            $stoks->where(function ($query) use ($search) {
                $query->whereHas('barang', function ($q) use ($search) {
                    $q->where('barang_nama', 'like', "%{$search}%")
                    ->orWhereHas('kategori', function ($qq) use ($search) {
                        $qq->where('kategori_nama', 'like', "%{$search}%");
                    });
                });
            });
        }

        return DataTables::of($stoks)
            ->addIndexColumn()
            ->addColumn('barang_nama', function ($stok) {
                return $stok->barang->barang_nama ?? '-';
            })
            ->addColumn('stok_tanggal', function ($stok) {
                return $stok->stok_tanggal ? $stok->stok_tanggal->format('Y-m-d') : '-';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


    public function store(Request $request)
    {
        $request->validate([
            'stok_id' => 'required|exists:t_stok,stok_id',
            'stok_jumlah' => 'required|integer|min:0'
        ]);
    
        try {
            $stok = StokModel::find($request->stok_id);
            $stok->stok_jumlah = $request->stok_jumlah;
            $stok->save();
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
