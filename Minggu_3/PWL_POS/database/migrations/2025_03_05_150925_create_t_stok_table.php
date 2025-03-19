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
        Schema::create('t_stok', function (Blueprint $table) {
            $table->bigIncrements('stok_id'); // Auto Increment hanya pada satu kolom
            $table->unsignedBigInteger('barang_id'); // Hapus parameter kedua (20)
            $table->unsignedBigInteger('user_id');
            $table->dateTime('stok_tanggal');
            $table->integer('stok_jumlah'); // Hapus parameter kedua (11)
            $table->timestamps();
    
            // Pastikan nama kolom sesuai dengan tabel referensinya
            $table->foreign('barang_id')->references('barang_id')->on('m_barang')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('m_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_stok', function (Blueprint $table) {
            //
        });
    }
};
