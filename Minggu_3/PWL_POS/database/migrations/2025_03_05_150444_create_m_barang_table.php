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
        Schema::create('m_barang', function (Blueprint $table) {
            $table->bigIncrements('barang_id'); // Auto Increment hanya di sini
            $table->unsignedBigInteger('kategori_id'); // Tidak perlu auto_increment
            $table->string('barang_kode', 10);
            $table->string('barang_nama', 100);
            $table->integer('harga_beli'); // Tidak perlu auto_increment
            $table->integer('harga_jual'); // Tidak perlu auto_increment
            $table->timestamps();

            $table->foreign('kategori_id')->references('kategori_id')->on('m_kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_barang', function (Blueprint $table) {
            //
        });
    }
};
