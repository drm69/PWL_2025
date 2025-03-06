<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        for ($i = 50; $i <= 59; $i++) {
            for ($j=1; $j < 4; $j++) { 
                $data[] = [
                    'penjualan_id' => ($i-10),
                    'barang_id' => ($i-30),
                    'harga' => 50000,
                    'jumlah' => $j,
                ];
            }
        }
        DB::table('t_penjualan_detail')->insert($data);
    }
}
