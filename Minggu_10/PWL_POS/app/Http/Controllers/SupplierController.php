<?php
 
 namespace App\Http\Controllers;
 
 use Illuminate\Http\Request;
 use App\Models\SupplierModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
 use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

 class SupplierController extends Controller
 {
     public function index()
     {
         $breadcrumb = (object) [
             'title' => 'Daftar Supplier',
             'list' => ['Home', 'Supplier']
         ];
 
         $page = (object) [
             'title' => 'Supplier Point of Sales'
         ];
 
         $activeMenu = 'supplier';
         $supplier = SupplierModel::all(); // Ambil semua data supplier untuk dropdown
 
         return view('supplier.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => $activeMenu]);
     }
 
     public function list(Request $request)
     {
         $suppliers = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'supplier_alamat', 'no_telepon');
 
         return DataTables::of($suppliers)
             ->addIndexColumn()
             ->addColumn('aksi', function ($supplier) {
                $btn = '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id) . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                 return $btn;
             })
             ->rawColumns(['aksi'])
             ->make(true);
     }
 
     public function create()
     {
         $breadcrumb = (object) [
             'title' => 'Tambah Supplier',
             'list' => ['Home', 'Supplier', 'Tambah']
         ];
 
         $page = (object) [
             'title' => 'Tambah supplier baru'
         ];
 
         $activeMenu = 'supplier';

         $prefix = 'SPR' . date('Ymd');
         $lastCode = DB::table('m_supplier')
             ->whereDate('created_at', now()->toDateString())
             ->orderByDesc('supplier_id')
             ->first();
     
         if ($lastCode) {
             $lastNumber = (int)substr($lastCode->supplier_kode, -4);
             $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
         } else {
             $newNumber = '0001';
         }
     
         $supplier_kode = $prefix . $newNumber;
 
         return view('supplier.create_ajax', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier_kode' => $supplier_kode, 'activeMenu' => $activeMenu]);
     }
 
     public function store(Request $request)
     {
         $request->validate([
             'supplier_nama' => 'required|string|max:100',
             'supplier_alamat' => 'required|string|max:255',
             'no_telepon' => 'required|string|max:15',
         ]);
 
         SupplierModel::create([
             'supplier_kode' => $request->supplier_kode,
             'supplier_nama' => $request->supplier_nama,
             'supplier_alamat' => $request->supplier_alamat,
             'no_telepon' => $request->no_telepon,
         ]);
 
         return redirect('/supplier')->with('success', 'Data supplier berhasil disimpan');
     }
 
     public function show(string $id)
     {
         $supplier = SupplierModel::find($id);
 
         $breadcrumb = (object) [
             'title' => 'Detail Supplier',
             'list' => ['Home', 'Supplier', 'Detail']
         ];
 
         $page = (object) [
             'title' => 'Detail supplier'
         ];
 
         $activeMenu = 'supplier';
 
         return view('supplier.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => $activeMenu]);
     }
 
     public function edit(string $id)
     {
         $supplier = SupplierModel::find($id);
 
         $breadcrumb = (object) [
             'title' => 'Edit Supplier',
             'list' => ['Home', 'Supplier', 'Edit']
         ];
 
         $page = (object) [
             'title' => 'Edit supplier'
         ];
 
         $activeMenu = 'supplier';
 
         return view('supplier.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => $activeMenu]);
     }
 
     public function update(Request $request, string $id)
     {
         $request->validate([
             'supplier_kode' => 'required|string|max:10|unique:m_supplier,supplier_kode,' . $id . ',supplier_id',
             'supplier_nama' => 'required|string|max:100',
             'supplier_alamat' => 'required|string|max:255',
             'no_telepon' => 'required|string|max:15',
         ]);
 
         SupplierModel::find($id)->update([
             'supplier_kode' => $request->supplier_kode,
             'supplier_nama' => $request->supplier_nama,
             'supplier_alamat' => $request->supplier_alamat,
             'no_telepon' => $request->no_telepon,
         ]);
 
         return redirect('/supplier')->with('success', 'Data supplier berhasil dirubah');
     }
 
     public function destroy(string $id)
     {
         $check = SupplierModel::find($id);
         if (!$check) {
             return redirect('/supplier')->with('error', 'Data supplier tidak ditemukan');
         }
 
         try {
             SupplierModel::destroy($id);
             return redirect('/supplier')->with('success', 'Data supplier berhasil dihapus');
         } catch (\Illuminate\Database\QueryException $e) {
             return redirect('/supplier')->with('error', 'Data supplier gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
         }
     }

     // tugas jb 6
     public function create_ajax()
     {
         return view('supplier.create_ajax');
     }
 
     public function store_ajax(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'supplier_nama' => 'required|string|max:100',
             'supplier_alamat' => 'required|string|max:255',
             'no_telepon' => 'required|string|max:15',
         ]);
     
         if ($validator->fails()) {
             return response()->json([
                 'status' => false,
                 'message' => 'Validasi gagal',
                 'msgField' => $validator->errors()
             ]);
         }
     
         try {
             SupplierModel::create([
                 'supplier_kode' => $request->supplier_kode,
                 'supplier_nama' => $request->supplier_nama,
                 'supplier_alamat' => $request->supplier_alamat,
                 'no_telepon' => $request->no_telepon,
             ]);
     
             return response()->json([
                 'status' => true,
                 'message' => 'Data supplier berhasil disimpan'
             ]);
         } catch (\Exception $e) {
             return response()->json([
                 'status' => false,
                 'message' => 'Gagal menyimpan data: ' . $e->getMessage()
             ]);
         }
     }     
 
     public function show_ajax(string $id)
     {
         $supplier = SupplierModel::find($id);
 
         return view('supplier.show_ajax', ['supplier' => $supplier]);
     }
 
     public function edit_ajax(string $id)
     {
         $supplier = SupplierModel::find($id);
 
         return view('supplier.edit_ajax', ['supplier' => $supplier]);
     }
 
     public function update_ajax(Request $request, $id)
     {
         // Mengecek apakah request berasal dari ajax
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'supplier_nama' => 'required|string|max:100',
                 'supplier_alamat' => 'required|string',
            ];
 
             // use Illuminate\Support\Facades\Validator;
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false, 
                     'message' => 'Validasi gagal. Periksa kembali data yang diinput.',
                     'msgField' => $validator->errors() // Menampilkan error pada field yang salah
                 ]);
             }
 
             try {
                 $supplier = SupplierModel::findOrFail($id); // Memastikan data ditemukan
                 $supplier->update($request->all());
 
                 return response()->json([
                     'status' => true,
                     'message' => 'Data berhasil diperbarui'
                 ]);
             } catch (\Illuminate\Database\QueryException $e) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.',
                     'error' => $e->getMessage() // Tampilkan pesan error 
                 ]);
             }
         }
 
         return redirect('/');
     }
 
 
     public function confirm_ajax(string $id)
     {
         $supplier = SupplierModel::find($id);
 
         return view('supplier.confirm_ajax', ['supplier' => $supplier]);
     }
 
     public function delete_ajax(Request $request, $id)
     {
         // cek apakah request dari ajax
         if ($request->ajax() || $request->wantsJson()) {
             $supplier = SupplierModel::find($id);
             if ($supplier) {
                 try {
                     $supplier->delete();
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
        $supplier = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'supplier_alamat', 'no_telepon')
            ->orderBy('supplier_id')
            ->get();

        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();  // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Supplier');
        $sheet->setCellValue('C1', 'Nama Supplier');
        $sheet->setCellValue('D1', 'Alamat');
        $sheet->setCellValue('E1', 'No Telepon');

        $sheet->getStyle('A1:E1')->getFont()->setBold(true);  // bold header

        $no = 1;
        $baris = 2;
        foreach ($supplier as $key => $value) {
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->supplier_kode);
            $sheet->setCellValue('C'.$baris, $value->supplier_nama);
            $sheet->setCellValue('D'.$baris, $value->supplier_alamat);
            $sheet->setCellValue('E'.$baris, $value->no_telepon);
            $baris++;
            $no++;
        }

        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Supplier'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Supplier'.date('Y-m-d H:i:s').'.xlsx';

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
        $supplier = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'supplier_alamat', 'no_telepon')
            ->orderBy('supplier_id')
            ->get();

        $pdf = Pdf::loadView('supplier.export_pdf', ['supplier' => $supplier]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->render();

        return $pdf->stream('Data Supplier'.date('Y-m-d H:i:s').'.pdf');
    }

    public function import()
    {
        return view('supplier.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_supplier' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_supplier');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);

            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];

            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if (strtolower(trim($value['A'])) == 'supplier_kode') continue;
                    if (empty($value['A'])) continue; // lewati baris kosong
                
                    $insert[] = [
                        'supplier_kode' => $value['A'],
                        'supplier_nama' => $value['B'],
                        'supplier_alamat' => $value['C'],
                        'no_telepon'  => $value['D'],
                        'created_at'  => now(),
                        'updated_at'  => now()
                    ];
                }

                Log::info('Final insert:', $insert);

                DB::beginTransaction();
                try {
                    foreach ($insert as $item) {
                        // Cek apakah barang sudah ada berdasarkan kode unik (misal barang_kode)
                        $supplier = SupplierModel::where('supplier_kode', $item['supplier_kode'])->first();
                    
                        if (!$supplier) {
                            $supplier = SupplierModel::create($item);
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

        return redirect('/supplier');
    }
 }