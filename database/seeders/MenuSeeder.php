<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            'Mix Topping Series' => [
                ['nama_produk' => 'Original (Basic tanpa topping)', 'harga_jual' => 12000],
                ['nama_produk' => 'Mix Biasa', 'harga_jual' => 15000],
                ['nama_produk' => 'Mix Pas Mantap', 'harga_jual' => 20000],
                ['nama_produk' => 'Mix Spesial', 'harga_jual' => 25000],
            ],
            'Pilihan Mie' => [
                ['nama_produk' => 'Mie Telor Biasa', 'harga_jual' => 0],
                ['nama_produk' => 'Mie Kwetiau', 'harga_jual' => 0],
                ['nama_produk' => 'Bihun', 'harga_jual' => 0],
                ['nama_produk' => 'Indomie Goreng', 'harga_jual' => 0],
                ['nama_produk' => 'Indomie Kareh', 'harga_jual' => 0],
                ['nama_produk' => 'Mie Bakmi', 'harga_jual' => 0],
            ],
            'Jenis Kuah' => [
                ['nama_produk' => 'Original Kuah', 'harga_jual' => 0],
                ['nama_produk' => 'Original Nyemek', 'harga_jual' => 0],
                ['nama_produk' => 'Asam Manis (Saos Kecap)', 'harga_jual' => 0],
            ],
            'Pilihan Level' => [
                ['nama_produk' => 'Level 0', 'harga_jual' => 0],
                ['nama_produk' => 'Level 0,5', 'harga_jual' => 0],
                ['nama_produk' => 'Level 1', 'harga_jual' => 0],
                ['nama_produk' => 'Level 2', 'harga_jual' => 0],
                ['nama_produk' => 'Level 3', 'harga_jual' => 2000],
                ['nama_produk' => 'Level 4', 'harga_jual' => 4000],
                ['nama_produk' => 'Level 5', 'harga_jual' => 6000],
                ['nama_produk' => 'Level 6', 'harga_jual' => 8000],
                ['nama_produk' => 'Level Pedas Setan', 'harga_jual' => 10000],
            ],
            'Topping Tambahan' => [
                ['nama_produk' => 'Sosis Kecil', 'harga_jual' => 4000],
                ['nama_produk' => 'Sosis Jumbo', 'harga_jual' => 13000],
                ['nama_produk' => 'Ceker (2 Biji)', 'harga_jual' => 5000],
                ['nama_produk' => 'Tulangan Ayam (Porsi)', 'harga_jual' => 5000],
                ['nama_produk' => 'Telur Orak Arik', 'harga_jual' => 4000],
                ['nama_produk' => 'Baso Sapi', 'harga_jual' => 2000],
                ['nama_produk' => 'Cilok (Porsi)', 'harga_jual' => 5000],
                ['nama_produk' => 'Dumpling Keju', 'harga_jual' => 3000],
                ['nama_produk' => 'Dumpling Ayam', 'harga_jual' => 3000],
                ['nama_produk' => 'Chikua', 'harga_jual' => 2000],
                ['nama_produk' => 'Burger Ikan', 'harga_jual' => 2000],
                ['nama_produk' => 'Bintang', 'harga_jual' => 2000],
                ['nama_produk' => 'Scallop', 'harga_jual' => 2000],
                ['nama_produk' => 'Tahu Baso Ikan', 'harga_jual' => 2000],
                ['nama_produk' => 'Odeng', 'harga_jual' => 3000],
                ['nama_produk' => 'Otak-otak Ikan', 'harga_jual' => 2000],
                ['nama_produk' => 'Otak-otak Singapore', 'harga_jual' => 13000],
                ['nama_produk' => 'Twister', 'harga_jual' => 4000],
                ['nama_produk' => 'Siomay', 'harga_jual' => 2000],
                ['nama_produk' => 'Enoki (Porsi)', 'harga_jual' => 3000],
            ],
        ];

        foreach ($menus as $kategoriName => $produkList) {
            $kategori = Kategori::firstOrCreate(['nama_kategori' => $kategoriName]);

            foreach ($produkList as $produk) {
                Produk::firstOrCreate(
                    ['nama_produk' => $produk['nama_produk']],
                    [
                        'id_kategori' => $kategori->id_kategori,
                        'id_cabang' => 1,
                        'kode_produk' => uniqid('P'),
                        'harga_beli' => 0,
                        'diskon' => 0,
                        'harga_jual' => $produk['harga_jual'],
                        'stok' => 999 
                    ]
                );
            }
        }
    }
}
