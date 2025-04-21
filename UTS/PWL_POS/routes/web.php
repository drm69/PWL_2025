<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransaksiController;
use App\Models\BarangModel;
use PHPUnit\Framework\Attributes\Group;

Route::pattern('id', '[0-9]+'); // artinya ketika ada parameter {id}, maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function() { // artinya semua route di dalam group ini harus login dulu
    // masukkan semua route yang perlu autentikasi di sini
    Route::get('/', [WelcomeController::class, 'index']);
    
    Route::group(['prefix' => 'user'], function () {
        Route::middleware(['authorize:ADM,MNG'])->Group(function() {
            Route::get('/', [UserController::class, 'index']); // menampilkan halaman awal user
            Route::get('/create_ajax', [UserController::class, 'create_ajax']); // menampilkan halaman form tambah user Ajax
            Route::post('/ajax', [UserController::class, 'store_ajax']); // menyimpan data user baru Ajax
            Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // menampilkan halaman form edit user Ajax
            Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // menyimpan perubahan data user Ajax
            Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // menampilkan halaman form Delete user Ajax
            Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // menghapus data user Ajax
            Route::post('/list', [UserController::class, 'list']); // menampilkan data user dalam bentuk json untuk datatables
            Route::get('/create', [UserController::class, 'create']); // menampilkan halaman form tambah user
            Route::post('/', [UserController::class, 'store']); // menyimpan data user baru
            Route::get('/{id}', [UserController::class, 'show']); // menampilkan detail user
            Route::get('/{id}/edit', [UserController::class, 'edit']); // menampilkan halaman form edit user
            Route::put('/{id}', [UserController::class, 'update']); // menyimpan perubahan data user
            Route::delete('/{id}', [UserController::class, 'destroy']); // menghapus data user
        });
    });
    
    Route::group(['prefix' => 'level'], function () {
        Route::middleware(['authorize:ADM'])->Group(function() {
            Route::get('/', [LevelController::class, 'index']);            
            Route::get('/create_ajax', [LevelController::class, 'create_ajax']); // menampilkan halaman form tambah user Ajax
            Route::post('/ajax', [LevelController::class, 'store_ajax']); // menyimpan data user baru Ajax
            Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); // menampilkan halaman form edit user Ajax
            Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']); // menyimpan perubahan data user Ajax
            Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); // menampilkan halaman form Delete user Ajax
            Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); // menghapus data user Ajax
            Route::post('/list', [LevelController::class, 'list']);        
            Route::get('/create', [LevelController::class, 'create']);    
            Route::post('/', [LevelController::class, 'store']);          
            Route::get('/{id}', [LevelController::class, 'show']);       
            Route::get('/{id}/edit', [LevelController::class, 'edit']);  
            Route::put("/{id}", [LevelController::class, 'update']);       
            Route::delete('/{id}', [LevelController::class, 'destroy']);
        });
    });
    
    Route::group(['prefix' => 'kategori'], function () {
        Route::middleware(['authorize:ADM,MNG,STF'])->Group(function() {
            Route::get('/', [KategoriController::class, 'index']);
            Route::get('/create_ajax', [KategoriController::class, 'create_ajax']); // menampilkan halaman form tambah user Ajax
            Route::post('/ajax', [KategoriController::class, 'store_ajax']); // menyimpan data user baru Ajax
            Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); // menampilkan halaman form edit user Ajax
            Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']); // menyimpan perubahan data user Ajax
            Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); // menampilkan halaman form Delete user Ajax
            Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); // menghapus data user Ajax
            Route::post('/list', [KategoriController::class, 'list']);
            Route::get('/create', [KategoriController::class, 'create']);
            Route::post('/', [KategoriController::class, 'store']);
            Route::get('/{id}', [KategoriController::class, 'show']);
            Route::get('/{id}/edit', [KategoriController::class, 'edit']);
            Route::put("/{id}", [KategoriController::class, 'update']);
            Route::delete('/{id}', [KategoriController::class, 'destroy']);
        });
    });
    
    Route::group(['prefix' => 'supplier'], function () {
        Route::get('/', [SupplierController::class, 'index']);
        Route::get('/create_ajax', [SupplierController::class, 'create_ajax']); // menampilkan halaman form tambah user Ajax
        Route::post('/ajax', [SupplierController::class, 'store_ajax']); // menyimpan data user baru Ajax
        Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); // menampilkan halaman form edit user Ajax
        Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']); // menyimpan perubahan data user Ajax
        Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); // menampilkan halaman form Delete user Ajax
        Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); // menghapus data user Ajax
        Route::post('/list', [SupplierController::class, 'list']);
        Route::get('/create', [SupplierController::class, 'create']);
        Route::post('/', [SupplierController::class, 'store']);
        Route::get('/{id}', [SupplierController::class, 'show']);
        Route::get('/{id}/edit', [SupplierController::class, 'edit']);
        Route::put("/{id}", [SupplierController::class, 'update']);
        Route::delete('/{id}', [SupplierController::class, 'destroy']);
    });

    Route::group(['prefix' => 'barang'], function () {
        Route::middleware(['authorize:ADM,MNG,STF'])->Group(function() {
            Route::get('/', [BarangController::class, 'index']);
            Route::get('/create_ajax', [BarangController::class, 'create_ajax']); // menampilkan halaman form tambah user Ajax
            Route::post('/ajax', [BarangController::class, 'store_ajax']); // menyimpan data user baru Ajax
            Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); // menampilkan halaman form edit user Ajax
            Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']); // menyimpan perubahan data user Ajax
            Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); // menampilkan halaman form Delete user Ajax
            Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); // menghapus data user Ajax
            Route::post('/list', [BarangController::class, 'list']);
            Route::get('/create', [BarangController::class, 'create']);
            Route::post('/', [BarangController::class, 'store']);
            Route::get('/{id}', [BarangController::class, 'show']);
            Route::get('/{id}/edit', [BarangController::class, 'edit']);
            Route::put("/{id}", [BarangController::class, 'update']);
            Route::delete('/{id}', [BarangController::class, 'destroy']);
            Route::get('/import', [BarangController::class, 'import']);
            Route::post('/import_ajax', [BarangController::class, 'import_ajax']);
            Route::get('/export_excel', [BarangController::class, 'export_excel']);
            Route::get('/export_pdf', [BarangController::class, 'export_pdf']);
        });
    });

    Route::group(['prefix' => 'stok'], function () {
        Route::middleware(['authorize:ADM,MNG,STF'])->Group(function() {
            Route::get('/', [StokController::class, 'index']);
            Route::post('/list', [StokController::class, 'list']);
            Route::get('/create', [StokController::class, 'create']);
            Route::post('/', [StokController::class, 'store']);
        });
    });
    
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [ProfileController::class, 'index']);
        Route::put('/update', [ProfileController::class, 'update']);
    });

    Route::group(['prefix' => 'transaksi'], function () {
        Route::middleware(['authorize:ADM,MNG,STF'])->Group(function() {
            Route::get('/', [TransaksiController::class, 'index']);
            Route::post('/list', [TransaksiController::class, 'list']);
            Route::get('/{id}', [TransaksiController::class, 'show']);
            Route::get('/export_excel', [TransaksiController::class, 'export_excel']);
            Route::get('/export_pdf', [TransaksiController::class, 'export_pdf']);
            Route::get('/create_ajax', [TransaksiController::class, 'create_ajax'])->name('create');
            Route::post('/ajax', [TransaksiController::class, 'store']);
            Route::get('/{id}/confirm_ajax', [TransaksiController::class, 'confirm_ajax']); // menampilkan halaman form Delete user Ajax
            Route::delete('/{id}/delete_ajax', [TransaksiController::class, 'delete_ajax']);
    
            // Mendapatkan barang berdasarkan kategori
            Route::get('/get-barang-by-kategori/{kategori_id}', function($kategori_id) {
                $barang = BarangModel::where('kategori_id', $kategori_id)
                    ->select('barang_id', 'barang_nama', 'harga_jual')
                    ->get();
                return response()->json(['status' => true, 'barang' => $barang]);
            });
            
            // Mendapatkan harga barang berdasarkan ID barang
            Route::get('/get-harga-barang/{barang_id}', function($barang_id) {
                $barang = BarangModel::find($barang_id);
                return response()->json(['status' => true, 'harga' => $barang->harga_jual]);
            });
        });
    });    
});
