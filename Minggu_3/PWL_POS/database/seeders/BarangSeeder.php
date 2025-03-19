<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'barang_id' => 21,
                'kategori_id' => 11,
                'barang_kode' => 'pns',
                'barang_nama' => 'pensil',
                'harga_beli' => 1000,
                'harga_jual' => 1500,
            ],
            [
                'barang_id' => 22,
                'kategori_id' => 11,
                'barang_kode' => 'blp',
                'barang_nama' => 'ballpoint',
                'harga_beli' => 2000,
                'harga_jual' => 2500,
            ],
            [
                'barang_id' => 23,
                'kategori_id' => 12,
                'barang_kode' => 'stk',
                'barang_nama' => 'steak',
                'harga_beli' => 50000,
                'harga_jual' => 60000,
            ],
            [
                'barang_id' => 24,
                'kategori_id' => 12,
                'barang_kode' => 'pzz',
                'barang_nama' => 'pizza',
                'harga_beli' => 40000,
                'harga_jual' => 45000,
            ],
            [
                'barang_id' => 25,
                'kategori_id' => 13,
                'barang_kode' => 'pnc',
                'barang_nama' => 'panci',
                'harga_beli' => 10000,
                'harga_jual' => 12000,
            ],
            [
                'barang_id' => 26,
                'kategori_id' => 13,
                'barang_kode' => 'spi',
                'barang_nama' => 'sapu ijuk',
                'harga_beli' => 8000,
                'harga_jual' => 10000,
            ],
            [
                'barang_id' => 27,
                'kategori_id' => 14,
                'barang_kode' => 'kbu',
                'barang_nama' => 'kabel utp',
                'harga_beli' => 5000,
                'harga_jual' => 7000,
            ],
            [
                'barang_id' => 28,
                'kategori_id' => 14,
                'barang_kode' => 'cnv',
                'barang_nama' => 'konverter',
                'harga_beli' => 120000,
                'harga_jual' => 140000,
            ],
            [
                'barang_id' => 29,
                'kategori_id' => 15,
                'barang_kode' => 'kmj',
                'barang_nama' => 'kemeja',
                'harga_beli' => 45000,
                'harga_jual' => 47000,
            ],
            [
                'barang_id' => 20,
                'kategori_id' => 15,
                'barang_kode' => 'lvi',
                'barang_nama' => 'celana levis',
                'harga_beli' => 60000,
                'harga_jual' => 70000,
            ],
        ];
        DB::table('m_barang')->insert($data);
    }
}
