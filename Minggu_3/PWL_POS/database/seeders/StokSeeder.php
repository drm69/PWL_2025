<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'stok_id' => 30,
                'barang_id' => 20,
                'user_id' => 1,
                'stok_tanggal' => now(),
                'stok_jumlah' => 24,
            ],
            [
                'stok_id' => 31,
                'barang_id' => 22,
                'user_id' => 1,
                'stok_tanggal' => now(),
                'stok_jumlah' => 20,
            ],
            [
                'stok_id' => 32,
                'barang_id' => 23,
                'user_id' => 1,
                'stok_tanggal' => now(),
                'stok_jumlah' => 12,
            ],
            [
                'stok_id' => 33,
                'barang_id' => 24,
                'user_id' => 1,
                'stok_tanggal' => now(),
                'stok_jumlah' => 33,
            ],
            [
                'stok_id' => 34,
                'barang_id' => 25,
                'user_id' => 1,
                'stok_tanggal' => now(),
                'stok_jumlah' => 32,
            ],
            [
                'stok_id' => 35,
                'barang_id' => 26,
                'user_id' => 1,
                'stok_tanggal' => now(),
                'stok_jumlah' => 11,
            ],
            [
                'stok_id' => 36,
                'barang_id' => 27,
                'user_id' => 1,
                'stok_tanggal' => now(),
                'stok_jumlah' => 9,
            ],
            [
                'stok_id' => 37,
                'barang_id' => 28,
                'user_id' => 1,
                'stok_tanggal' => now(),
                'stok_jumlah' => 23,
            ],
            [
                'stok_id' => 38,
                'barang_id' => 29,
                'user_id' => 1,
                'stok_tanggal' => now(),
                'stok_jumlah' => 26,
            ],
            [
                'stok_id' => 39,
                'barang_id' => 21,
                'user_id' => 1,
                'stok_tanggal' => now(),
                'stok_jumlah' => 28,
            ],
        ];
        DB::table('t_stok') -> insert($data);
    }
}
