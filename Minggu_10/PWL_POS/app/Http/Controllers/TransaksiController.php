<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class TransaksiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) ['title' => 'Transaksi Penjualan', 'list' => ['Home', 'Transaksi']];
        $page = (object) ['title' => 'Daftar Transaksi'];
        $activeMenu = 'transaksi';
        $detail = PenjualanDetailModel::all();

        return view('transaksi.index', compact('breadcrumb', 'page', 'activeMenu', 'detail'));
    }

    public function list(Request $request)
    {
        $penjualan = PenjualanModel::with('user')->select('*');

        if (!empty($request->search['value'])) {
            $search = $request->search['value'];

            $penjualan->where(function ($q) use ($search) {
                $q->where('penjualan_kode', 'like', "%{$search}%")
                ->orWhere('pembeli', 'like', "%{$search}%")
                ->orWhere('penjualan_tanggal', 'like', "%{$search}%")
                ->orWhereHas('user', function ($qu) use ($search) {
                    $qu->where('nama', 'like', "%{$search}%");
                });
            });
        }

        return DataTables::of($penjualan)
            ->addIndexColumn()
            ->addColumn('user_nama', function ($row) {
                return $row->user->nama ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . url('/transaksi/' . $row->penjualan_id) . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/transaksi/' . $row->penjualan_id . '/confirm_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
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
         $prefix = 'TRX' . date('Ymd');
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
         try {
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
     
             return response()->json([
                 'status' => true,
                 'message' => 'Data transaksi berhasil disimpan.'
             ]);
         } catch (\Exception $e) {
             return response()->json([
                 'status' => false,
                 'message' => 'Terjadi kesalahan saat menyimpan transaksi.',
                 'error' => $e->getMessage()
             ], 500);
         }
     }
     
     public function export_excel() {
        $transaksi = PenjualanDetailModel::select('detail_id', 'penjualan_id', 'barang_id', 'harga', 'jumlah')
            ->orderBy('detail_id')
            ->with(['penjualan.user', 'barang']) // Load relasi user lewat penjualan
            ->get();

        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();  // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Transaksi');
        $sheet->setCellValue('C1', 'Kasir');
        $sheet->setCellValue('D1', 'Pembeli');
        $sheet->setCellValue('E1', 'Tanggal');
        $sheet->setCellValue('F1', 'Harga');
        $sheet->setCellValue('G1', 'Jumlah');
        $sheet->setCellValue('H1', 'Total');

        $sheet->getStyle('A1:H1')->getFont()->setBold(true);  // bold header

        $no = 1;
        $baris = 2;
        foreach ($transaksi as $key => $value) {
            $total = $value->harga * $value->jumlah;

            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->penjualan->penjualan_kode);
            $sheet->setCellValue('C'.$baris, $value->penjualan->user->nama);
            $sheet->setCellValue('D'.$baris, $value->penjualan->pembeli);
            $sheet->setCellValue('E'.$baris, $value->penjualan->penjualan_tanggal);
            $sheet->setCellValue('F'.$baris, 'Rp ' . number_format($value->harga, 0, ',', '.'));
            $sheet->setCellValue('G'.$baris, $value->jumlah);
            $sheet->setCellValue('H'.$baris, 'Rp ' . number_format($total, 0, ',', '.'));
    
            $baris++;
            $no++;
        }

        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Transaksi'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Transaksi'.date('Y-m-d H:i:s').'.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
        // end function export excel
    }
    public function export_pdf()
    {
        $transaksi = PenjualanDetailModel::with(['penjualan.user', 'barang'])
            ->orderBy('detail_id')
            ->get();

        $pdf = Pdf::loadView('transaksi.export_pdf', ['transaksi' => $transaksi]);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('Data Transaksi ' . date('Y-m-d H:i:s') . '.pdf');
    }

    public function confirm_ajax(string $id){
        $transaksi = PenjualanModel::with('user')->find($id);

        return view('transaksi.confirm_ajax', ['transaksi' => $transaksi]);
    }

    public function delete_ajax(request $request, $id)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $transaksi = PenjualanModel::find($id);
             if ($transaksi) {
                 $transaksi->delete();
                 return response()->json([
                     'status' => true,
                     'message' => 'Data berhasil dihapus'
                 ]);
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'Data tidak ditemukan'
                 ]);
             }
         }
 
         return redirect('transaksi');
     }
}