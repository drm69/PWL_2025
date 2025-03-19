<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('t_penjualan_detail', function (Blueprint $table) {
            $table->bigIncrements('detail_id'); // Auto Increment hanya pada satu kolom
            $table->unsignedBigInteger('penjualan_id'); // Hapus parameter kedua (20)
            $table->unsignedBigInteger('barang_id');
            $table->integer('harga'); // Hapus parameter kedua (11)
            $table->integer('jumlah');
            $table->timestamps();
    
            // Pastikan nama kolom sesuai dengan tabel referensinya
            $table->foreign('penjualan_id')->references('penjualan_id')->on('t_penjualan')->onDelete('cascade');
            $table->foreign('barang_id')->references('barang_id')->on('m_barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_penjualan_detail', function (Blueprint $table) {
            //
        });
    }
};
