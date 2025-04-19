<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\StokModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BarangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) ['title' => 'Data Barang', 'list' => ['Home', 'Barang']];
        $page = (object) ['title' => 'Daftar Barang'];
        $activeMenu = 'barang';
        $kategori = KategoriModel::all();

        return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
     {
         $barangs = BarangModel::select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
             ->with('kategori');
 
         if ($request->kategori_id) {
             $barangs->where('kategori_id', $request->kategori_id);
         }
 
         return DataTables::of($barangs)
             ->addIndexColumn()
             ->addColumn('kategori_nama', function ($barang) {
                return $barang->kategori->kategori_nama ?? '-';
            })
             ->addColumn('aksi', function ($barang) {
                //  $btn = '<a href="' . url('/barang/' . $barang->barang_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                //  $btn .= '<a href="' . url('/barang/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                //  $btn .= '<form class="d-inline-block" method="POST" action="' . url('/barang/' . $barang->barang_id) . '">' .
                //      csrf_field() . method_field('DELETE') .
                //      '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';

                // tugas jb 6
                $btn = '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                 return $btn;
             })
             ->rawColumns(['aksi'])
             ->make(true);
     }


    public function create()
    {
        $breadcrumb = (object) ['title' => 'Tambah Barang', 'list' => ['Home', 'Barang', 'Tambah']];
        $page = (object) ['title' => 'Form Tambah Barang'];
        $activeMenu = 'barang';
        $kategori = KategoriModel::all();

        return view('barang.create', compact('breadcrumb', 'page', 'activeMenu', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id'  => 'required|exists:m_kategori,kategori_id',
            'barang_kode'  => 'required|string|max:10|unique:m_barang,barang_kode',
            'barang_nama'  => 'required|string|max:100',
            'harga_beli'   => 'required|integer|min:0',
            'harga_jual'   => 'required|integer|min:0',
        ]);

        BarangModel::create($request->only(['kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual']));

        return redirect('/barang')->with('success', 'Data berhasil disimpan');
    }

    public function show($id)
    {
        $barang = BarangModel::with('kategori')->findOrFail($id);

        $breadcrumb = (object) ['title' => 'Detail Barang', 'list' => ['Home', 'Barang', 'Detail']];
        $page = (object) ['title' => 'Detail Data Barang'];
        $activeMenu = 'barang';

        return view('barang.show', compact('breadcrumb', 'page', 'activeMenu', 'barang'));
    }

    public function edit($id)
    {
        $barang = BarangModel::findOrFail($id);
        $breadcrumb = (object) ['title' => 'Edit Barang', 'list' => ['Home', 'Barang', 'Edit']];
        $page = (object) ['title' => 'Edit Data Barang'];
        $activeMenu = 'barang';
        $kategori = KategoriModel::all();

        return view('barang.edit', compact('breadcrumb', 'page', 'activeMenu', 'barang', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_id'  => 'required|exists:m_kategori,kategori_id',
            'barang_kode'  => 'required|string|max:10|unique:m_barang,barang_kode,' . $id . ',barang_id',
            'barang_nama'  => 'required|string|max:100',
            'harga_beli'   => 'required|integer|min:0',
            'harga_jual'   => 'required|integer|min:0',
        ]);

        BarangModel::findOrFail($id)->update($request->only(['kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual']));

        return redirect('/barang')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $check = BarangModel::find($id);
        if (!$check) {
            return redirect('/level')->with('error', 'Data level tidak ditemukan');
        }

        try {
            BarangModel::destroy($id);
            return redirect('/barang')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/barang')->with('error', 'Data gagal dihapus: ' . $e->getMessage());
        }
    }

    public function create_ajax()
     {
         $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
 
         return view('barang.create_ajax')
             ->with('kategori', $kategori);
     }
 
     public function store_ajax(Request $request)
     {
         // Mengecek apakah request berupa ajax
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'barang_kode' => 'required|string|min:3|max:10|unique:m_barang,barang_kode',
                 'barang_nama' => 'required|string|max:100',
                 'harga_beli' => 'required|integer',
                 'harga_jual' => 'required|integer',
                 'kategori_id' => 'required|integer'
             ];
 
             // use Illuminate\Support\Facades\Validator;
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false, // response status, false: error/gagal, true: berhasil
                     'message' => 'Validasi Gagal',
                     'msgField' => $validator->errors() // pesan error validasi
                 ]);
             }
 
             BarangModel::create($request->all());
 
             return response()->json([
                 'status' => true,
                 'message' => 'Data barang berhasil disimpan'
             ]);
         }
         return redirect('/');
     }
 
     public function show_ajax(string $id)
     {
         $barang = BarangModel::with('kategori')->find($id);
 
         return view('barang.show_ajax', ['barang' => $barang]);
     }
 
     public function edit_ajax(string $id)
     {
         $barang = BarangModel::find($id);
         $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
 
         return view('barang.edit_ajax', ['barang' => $barang, 'kategori' => $kategori]);
     }
 
     public function update_ajax(Request $request, $id)
     {
         // Mengecek apakah request dari ajax
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'barang_kode' => 'required|string|min:3|max:10|unique:m_barang,barang_kode,' . $id . ',barang_id',
                 'barang_nama' => 'required|string|max:100',
                 'harga_beli' => 'required|integer',
                 'harga_jual' => 'required|integer',
                 'kategori_id' => 'required|integer'
             ];
 
             // use Illuminate\Support\Facades\Validator;
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false, // respon json, true: berhasil, false: gagal
                     'message' => 'Validasi gagal.',
                     'msgField' => $validator->errors() // Memperlihatkan field mana yang error
                 ]);
             }
 
             try {
                 BarangModel::find($id)->update($request->all());
                 return response()->json([
                     'status' => true,
                     'message' => 'Data berhasil diupdate'
                 ]);
             } catch (\Illuminate\Database\QueryException $e) {
                 return response()->json([
                     'status' => false, // respon json, true: berhasil, false: gagal
                     'message' => 'Validasi gagal.',
                     'msgField' => $validator->errors() // Memperlihatkan field mana yang error
                 ]);
             }
         }
         return redirect('/');
     }
 
     public function confirm_ajax(string $id)
     {
         $barang = BarangModel::find($id);
 
         return view('barang.confirm_ajax', ['barang' => $barang]);
     }
 
     public function delete_ajax(Request $request, $id)
     {
         // Mengecek apakah request dari ajax
         if ($request->ajax() || $request->wantsJson()) {
             $barang = BarangModel::find($id);
             if ($barang) {
                 try {
                     $barang->delete();
                     return response()->json([
                         'status' => true,
                         'message' => 'Data berhasil dihapus'
                     ]);
                 } catch (\Illuminate\Database\QueryException $e) {
                     return response()->json([
                         'status' => false,
                         'message' => 'Data tidak bisa dihapus'
                     ]);
                 }
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'Data tidak ditemukan'
                 ]);
             }
         }
         return redirect('/');
    }
    
    public function import()
    {
        return view('barang.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_barang' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_barang');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);

            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];

            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris != 0 && strtolower(trim($value['A'])) != 'kategori_id') {
                        $insert[] = [
                            'kategori_id' => $value['A'],
                            'barang_kode' => $value['B'],
                            'barang_nama' => $value['C'],
                            'harga_beli'  => $value['D'],
                            'harga_jual'  => $value['E'],
                            'created_at'  => now(),
                            'updated_at'  => now()
                        ];
                    }
                }

                Log::info('Final insert:', $insert);

                DB::beginTransaction();
                try {
                    foreach ($insert as $item) {
                        // Cek apakah barang sudah ada berdasarkan kode unik (misal barang_kode)
                        $barang = BarangModel::where('barang_kode', $item['barang_kode'])->first();
                    
                        if (!$barang) {
                            $barang = BarangModel::create($item);
                        }
                    
                        // Cek apakah stok untuk barang ini sudah pernah dibuat
                        $stokAda = StokModel::where('barang_id', $barang->barang_id)->exists();
                    
                        if (!$stokAda) {
                            StokModel::create([
                                'barang_id'    => $barang->barang_id,
                                'user_id'      => Auth::id() ?? 1,
                                'stok_tanggal' => now(),
                                'stok_jumlah'  => 0
                            ]);
                        }
                    }                    

                    DB::commit();

                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diimport'
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Insert gagal: ' . $e->getMessage());
                    return response()->json([
                        'status' => false,
                        'message' => 'Insert gagal: ' . $e->getMessage()
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }

        return redirect('/barang');
    }

    public function export_excel() {
        $barang = BarangModel::select('kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
            ->orderBy('kategori_id')
            ->with('kategori')
            ->get();

        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();  // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Barang');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Harga Beli');
        $sheet->setCellValue('E1', 'Harga Jual');
        $sheet->setCellValue('F1', 'Kategori');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);  // bold header

        $no = 1;
        $baris = 2;
        foreach ($barang as $key => $value) {
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->barang_kode);
            $sheet->setCellValue('C'.$baris, $value->barang_nama);
            $sheet->setCellValue('D'.$baris, $value->harga_beli);
            $sheet->setCellValue('E'.$baris, $value->harga_jual);
            $sheet->setCellValue('F'.$baris, $value->kategori->kategori_nama);
            $baris++;
            $no++;
        }

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Barang'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Barang'.date('Y-m-d H:i:s').'.xlsx';

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
    public function export_pdf() {
        $barang = BarangModel::select('kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
            ->orderBy('kategori_id')
            ->with('kategori')
            ->get();

        $pdf = Pdf::loadView('barang.export_pdf', ['barang' => $barang]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->render();

        return $pdf->stream('Data Barang'.date('Y-m-d H:i:s').'.pdf');
    }
}