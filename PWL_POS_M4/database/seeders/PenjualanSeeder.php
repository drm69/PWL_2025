<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'penjualan_id' => 40,
                'user_id' => 3,
                'pembeli' => 'Danang',
                'penjualan_kode' => 'ab1',
                'penjualan_tanggal' => now(),
            ],
            [
                'penjualan_id' => 41,
                'user_id' => 3,
                'pembeli' => 'yanto',
                'penjualan_kode' => 'ab2',
                'penjualan_tanggal' => now(),
            ],
            [
                'penjualan_id' => 42,
                'user_id' => 3,
                'pembeli' => 'supri',
                'penjualan_kode' => 'ab3',
                'penjualan_tanggal' => now(),
            ],
            [
                'penjualan_id' => 43,
                'user_id' => 3,
                'pembeli' => 'Danang',
                'penjualan_kode' => 'ab4',
                'penjualan_tanggal' => now(),
            ],
            [
                'penjualan_id' => 44,
                'user_id' => 3,
                'pembeli' => 'michelle',
                'penjualan_kode' => 'ab5',
                'penjualan_tanggal' => now(),
            ],
            [
                'penjualan_id' => 45,
                'user_id' => 3,
                'pembeli' => 'sinta',
                'penjualan_kode' => 'ab6',
                'penjualan_tanggal' => now(),
            ],
            [
                'penjualan_id' => 46,
                'user_id' => 3,
                'pembeli' => 'budi',
                'penjualan_kode' => 'ab7',
                'penjualan_tanggal' => now(),
            ],
            [
                'penjualan_id' => 47,
                'user_id' => 3,
                'pembeli' => 'dinda',
                'penjualan_kode' => 'ab8',
                'penjualan_tanggal' => now(),
            ],
            [
                'penjualan_id' => 48,
                'user_id' => 3,
                'pembeli' => 'hansen',
                'penjualan_kode' => 'ab9',
                'penjualan_tanggal' => now(),
            ],
            [
                'penjualan_id' => 49,
                'user_id' => 3,
                'pembeli' => 'ibnu',
                'penjualan_kode' => 'bb1',
                'penjualan_tanggal' => now(),
            ],
        ];
        DB::table('t_penjualan') -> insert($data);
    }
}
