<?php

namespace App\Http\Controllers;

use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransaksiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) ['title' => 'Transaksi Penjualan', 'list' => ['Home', 'Transaksi']];
        $page = (object) ['title' => 'Daftar Transaksi'];
        $activeMenu = 'transaksi';

        return view('transaksi.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $penjualan = PenjualanModel::with('user')->select('*');

        if ($request->search['value']) {
            $search = $request->search['value'];
            $penjualan->where(function ($q) use ($search) {
                $q->where('penjualan_kode', 'like', "%{$search}%")
                  ->orWhere('pembeli', 'like', "%{$search}%");
            });
        }

        return DataTables::of($penjualan)
            ->addIndexColumn()
            ->addColumn('user_nama', function ($row) {
                return $row->user->nama ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                //  $btn = '<a href="' . url('/kategori/' . $kategori->kategori_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                //  $btn .= '<a href="' . url('/kategori/' . $kategori->kategori_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                //  $btn .= '<form class="d-inline-block" method="POST" action="' . url('/kategori/' . $kategori->kategori_id) . '">' .
                //      csrf_field() . method_field('DELETE') .
                //      '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                // jb 6 tugas
                $btn = '<button onclick="modalAction(\'' . url('/transaksi/' . $row->kategori_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $row->kategori_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $row->kategori_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                 return $btn;
             })       
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show(string $id)
     {
        $detail = PenjualanDetailModel::with(['penjualan', 'barang'])
            ->where('penjualan_id', $id)
            ->first();
 
         $breadcrumb = (object) [
             'title' => 'Detail Transaksi',
             'list' => ['Home', 'Transaksi', 'Detail']
         ];
 
         $page = (object) [
             'title' => 'Detail transaksi'
         ];
 
         $activeMenu = 'transaksi';
 
         return view('transaksi.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'transaksi' => $detail, 'activeMenu' => $activeMenu]);
     }
}