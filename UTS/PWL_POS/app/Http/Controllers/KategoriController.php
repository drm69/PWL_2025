<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class KategoriController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) ['title' => 'Data Kategori', 'list' => ['Home', 'Kategori']];
        $page = (object) ['title' => 'Daftar Kategori'];
        $activeMenu = 'kategori';

        return view('kategori.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
     {
         $kategoris = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');
 
         // Filter berdasarkan kategori_kode
         if ($request->kategori_kode) {
             $kategoris->where('kategori_kode', $request->kategori_kode);
         }
 
         return DataTables::of($kategoris)
             ->addIndexColumn()
             ->addColumn('aksi', function ($kategori) {
                $btn = '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id) . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                 return $btn;
             })
             ->rawColumns(['aksi'])
             ->make(true);
     }

    public function create()
    {
        $breadcrumb = (object) ['title' => 'Tambah Kategori', 'list' => ['Home', 'Kategori', 'Tambah']];
        $page = (object) ['title' => 'Form Tambah Kategori'];
        $activeMenu = 'kategori';

        return view('kategori.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_kode' => 'required|string|max:10|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|string|max:100',
        ]);

        KategoriModel::create($request->only(['kategori_kode', 'kategori_nama']));

        return redirect('/kategori')->with('success', 'Data berhasil disimpan');
    }

    public function show($id)
    {
        $kategori = KategoriModel::findOrFail($id);

        $breadcrumb = (object) ['title' => 'Detail Kategori', 'list' => ['Home', 'Kategori', 'Detail']];
        $page = (object) ['title' => 'Detail Data Kategori'];
        $activeMenu = 'kategori';

        return view('kategori.show', compact('breadcrumb', 'page', 'activeMenu', 'kategori'));
    }

    public function edit($id)
    {
        $kategori = KategoriModel::findOrFail($id);

        $breadcrumb = (object) ['title' => 'Edit Kategori', 'list' => ['Home', 'Kategori', 'Edit']];
        $page = (object) ['title' => 'Edit Data Kategori'];
        $activeMenu = 'kategori';

        return view('kategori.edit', compact('breadcrumb', 'page', 'activeMenu', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_kode' => 'required|string|max:10|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
            'kategori_nama' => 'required|string|max:100',
        ]);

        KategoriModel::findOrFail($id)->update($request->only(['kategori_kode', 'kategori_nama']));

        return redirect('/kategori')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $check = KategoriModel::find($id);
        if (!$check) {
            return redirect('/level')->with('error', 'Data level tidak ditemukan');
        }

        try {
            KategoriModel::destroy($id);
            return redirect('/kategori')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/kategori')->with('error', 'Data gagal dihapus: ' . $e->getMessage());
        }
    }

    public function create_ajax()
     {
         return view('kategori.create_ajax');
     }
 
     public function store_ajax(Request $request)
     {
         // Mengecek apakah request berupa ajax
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'kategori_kode' => 'required|string|min:3|unique:m_kategori,kategori_kode',
                 'kategori_nama' => 'required|string|max:100'
             ];
 
             // use Illuminate\Support\Facades\Validator;
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false, // response status, false: error/gagal, true: berhasil
                     'message' => 'Validasi Gagal',
                     'msgField' => $validator->errors() // Validasi pesan error
                 ]);
             }
             
             KategoriModel::create($request->all());
 
             return response()->json([
                 'status' => true,
                 'message' => 'Data kategori berhasil disimpan'
             ]);
         }
         return redirect('/');
     }
     public function show_ajax(string $id)
     {
         $kategori = KategoriModel::find($id);
 
         return view('kategori.show_ajax', ['kategori' => $kategori]);
     }
 
     public function edit_ajax(string $id)
     {
         $kategori = KategoriModel::find($id);
 
         return view('kategori.edit_ajax', ['kategori' => $kategori]);
     }
 
     public function update_ajax(Request $request, $id)
     {
         // Mengecek apakah request dari ajax
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'kategori_kode' => 'required|string|min:3|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
                 'kategori_nama' => 'required|string|max:100'
             ];
 
             // use Illuminate\Support\Facades\Validator;
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false, // respon json, true: berhasil, false: gagal
                     'message' => 'Validasi gagal.',
                     'msgField' => $validator->errors() // Menunjukkan field mana yang error
                 ]);
             }
 
             try {
                 KategoriModel::find($id)->update($request->all());
                 return response()->json([
                     'status' => true,
                     'message' => 'Data berhasil diupdate'
                 ]);
             } catch (\Illuminate\Database\QueryException $e) {
                 return response()->json([
                     'status' => false, // respon json, true: berhasil, false: gagal
                     'message' => 'Validasi gagal.',
                     'msgField' => $validator->errors() // Menunjukkan field mana yang error
                 ]);
             }
         }
         return redirect('/');
     }
 
     public function confirm_ajax(string $id)
     {
         $kategori = KategoriModel::find($id);
 
         return view('kategori.confirm_ajax', ['kategori' => $kategori]);
     }

     public function delete_ajax(Request $request, $id)
     {
         // Mengecek apakah request dari ajax
         if ($request->ajax() || $request->wantsJson()) {
             $level = KategoriModel::find($id);
             if ($level) {
                 try {
                     $level->delete();
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

     public function export_excel() {
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama')
            ->orderBy('kategori_id')
            ->get();

        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();  // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID');
        $sheet->setCellValue('C1', 'Kode Kategori');
        $sheet->setCellValue('D1', 'Nama Kategori');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);  // bold header

        $no = 1;
        $baris = 2;
        foreach ($kategori as $key => $value) {
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->kategori_id);
            $sheet->setCellValue('C'.$baris, $value->kategori_kode);
            $sheet->setCellValue('D'.$baris, $value->kategori_nama);
            $baris++;
            $no++;
        }

        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Kategori'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Kategori'.date('Y-m-d H:i:s').'.xlsx';

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
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama')
            ->orderBy('kategori_id')
            ->get();

        $pdf = Pdf::loadView('kategori.export_pdf', ['kategori' => $kategori]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->render();

        return $pdf->stream('Data Kategori'.date('Y-m-d H:i:s').'.pdf');
    }

    public function import()
    {
        return view('kategori.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_kategori' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_kategori');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);

            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];

            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if (strtolower(trim($value['A'])) == 'kategori_kode') continue;
                    if (empty($value['A'])) continue; // lewati baris kosong
                
                    $insert[] = [
                        'kategori_kode' => $value['A'],
                        'kategori_nama' => $value['B'],
                        'created_at'  => now(),
                        'updated_at'  => now()
                    ];
                }

                Log::info('Final insert:', $insert);

                DB::beginTransaction();
                try {
                    foreach ($insert as $item) {
                        // Cek apakah barang sudah ada berdasarkan kode unik (misal barang_kode)
                        $kategori = KategoriModel::where('kategori_kode', $item['kategori_kode'])->first();
                    
                        if (!$kategori) {
                            $kategori = KategoriModel::create($item);
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

        return redirect('/kategori');
    }
}