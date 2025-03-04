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
                'barang_id' => 1,
                'kategori_id' => 1,
                'barang_kode' => 1,
                'barang_nama' => 'Indomie Goreng',
                'harga_beli' => 3000,
                'harga_jual' => 3500,
            ],
            [
                'barang_id' => 2,
                'kategori_id' => 4,
                'barang_kode' => 2,
                'barang_nama' => 'Lampu LED',
                'harga_beli' => 20000,
                'harga_jual' => 22000,
            ],
            [
                'barang_id' => 3,
                'kategori_id' => 2,
                'barang_kode' => 3,
                'barang_nama' => 'Celana Jeans',
                'harga_beli' => 90000,
                'harga_jual' => 10000,
            ],
            [
                'barang_id' => 4,
                'kategori_id' => 5,
                'barang_kode' => 4,
                'barang_nama' => 'Paratusin',
                'harga_beli' => 15000,
                'harga_jual' => 17000,
            ],
            [
                'barang_id' => 5,
                'kategori_id' => 3,
                'barang_kode' => 5,
                'barang_nama' => 'Sapu Ijuk',
                'harga_beli' => 25000,
                'harga_jual' => 30000,
            ],
            [
                'barang_id' => 6,
                'kategori_id' => 1,
                'barang_kode' => 6,
                'barang_nama' => 'Wafer Nabati',
                'harga_beli' => 6000,
                'harga_jual' => 7000,
            ],
            [
                'barang_id' => 7,
                'kategori_id' => 2,
                'barang_kode' => 7,
                'barang_nama' => 'Kemeja Salur',
                'harga_beli' => 100000,
                'harga_jual' => 115000,
            ],
            [
                'barang_id' => 8,
                'kategori_id' => 1,
                'barang_kode' => 8,
                'barang_nama' => 'Krabby Patty',
                'harga_beli' => 20000,
                'harga_jual' => 22000,
            ],
            [
                'barang_id' => 9,
                'kategori_id' => 1,
                'barang_kode' => 9,
                'barang_nama' => 'Sosis Kanzler Gochujang',
                'harga_beli' => 9000,
                'harga_jual' => 10000,
            ],
            [
                'barang_id' => 10,
                'kategori_id' => 1,
                'barang_kode' => 10,
                'barang_nama' => 'Cimory Yogurt',
                'harga_beli' => 9000,
                'harga_jual' => 10000,
            ],
        ];
        DB::table('m_barang')->insert($data);
    }
}
