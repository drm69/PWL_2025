<?php

use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/kategori', [KategoriController::class, 'index'])->name('Kategori');
Route::get('/kategori/create', [KategoriController::class, 'create'])->name('addKategori');
Route::delete('/kategori/hapus/{id}', [KategoriController::class, 'delete'])->name('deleteKategori');
Route::get('/kategori/edit/{id}', [KategoriController::class, 'edit'])->name('editKategori');
Route::put('/kategori/save/{id}', [KategoriController::class, 'save'])->name('saveKategori');
Route::post('/kategori', [KategoriController::class, 'store']);

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']); // Menampilkan halaman awal user
    Route::post('/list', [UserController::class, 'list']); // Menampilkan data user dalam bentuk json untuk datatables
    Route::get('/create', [UserController::class, 'create']); // Menampilkan halaman form tambah user
    Route::post('/', [UserController::class, 'store']); // Menyimpan data user baru

    Route::get('/create_ajax', [UserController::class, 'create_ajax'])->name('addUser'); // Menampilkan halaman form tambah user Ajax
    Route::post('/ajax', [UserController::class, 'store_ajax'])->name('saveUser'); // Menyimpan data user baru Ajax

    Route::get('/{id}', [UserController::class, 'show']); // Menampilkan detail user
    Route::get('/{id}/edit', [UserController::class, 'edit_ajax'])->name('editUser'); // Menampilkan halaman form edit user
    Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax'])->name('deleteUser'); // Menampilkan halaman form edit user
    Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Menampilkan halaman form edit user
    Route::put('/{id}', [UserController::class, 'update_ajax'])->name('save'); // Menyimpan perubahan data user
    Route::delete('/{id}', [UserController::class, 'destroy']); // Menghapus data user
});
