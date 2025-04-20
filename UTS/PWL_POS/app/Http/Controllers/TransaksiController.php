<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

     public function create_ajax()
     {
         $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
         $barang = BarangModel::select('barang_id', 'barang_nama', 'harga_jual', 'kategori_id')
             ->with('kategori')
             ->get();
     
         // Generate kode penjualan otomatis
         $prefix = 'TRX' . date('Ymd'); // Contoh: TRX20250421
         $lastCode = DB::table('t_penjualan')
             ->whereDate('created_at', now()->toDateString())
             ->orderByDesc('penjualan_id')
             ->first();
     
         if ($lastCode) {
             $lastNumber = (int)substr($lastCode->penjualan_kode, -4);
             $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
         } else {
             $newNumber = '0001';
         }
     
         $penjualan_kode = $prefix . $newNumber;
     
         return view('transaksi.create_ajax')
             ->with('kategori', $kategori)
             ->with('barang', $barang)
             ->with('penjualan_kode', $penjualan_kode);
     }

     public function store(Request $request)
     {
         // Simpan data penjualan dan ambil objeknya
         $penjualan = PenjualanModel::create([
             'user_id' => Auth::id(),
             'pembeli' => $request->pembeli,
             'penjualan_kode' => $request->penjualan_kode,
             'penjualan_tanggal' => now(),
         ]);
     
         // Simpan detail penjualan menggunakan ID dari penjualan yang baru dibuat
         PenjualanDetailModel::create([
             'penjualan_id' => $penjualan->penjualan_id,
             'barang_id' => $request->barang_id,
             'harga' => $request->harga,
             'jumlah' => $request->jumlah,
         ]);
     
         return redirect('/transaksi')->with('success', 'Data transaksi berhasil disimpan');
     }     
}