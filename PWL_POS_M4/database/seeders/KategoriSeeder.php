<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategori_id' => 11,
                'kategori_kode' => 'atk',
                'kategori_nama' => 'Alat Tulis',
            ],
            [
                'kategori_id' => 12,
                'kategori_kode' => 'fnb',
                'kategori_nama' => 'Food and Beverage',
            ],
            [
                'kategori_id' => 13,
                'kategori_kode' => 'art',
                'kategori_nama' => 'Alat Rumah Tangga',
            ],
            [
                'kategori_id' => 14,
                'kategori_kode' => 'elc',
                'kategori_nama' => 'Elektronik',
            ],
            [
                'kategori_id' => 15,
                'kategori_kode' => 'clh',
                'kategori_nama' => 'Pakaian',
            ],
        ];
        DB::table('m_kategori')->insert($data);
    }
}
