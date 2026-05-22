<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CabangTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cabang')->updateOrInsert(['id' => 1], [
            'nama_cabang' => 'STALKUDA',
            'alamat' => 'Jalan Jendral Sudirman, Gn. Bahagia, Kecamatan Balikpapan Selatan, Kota Balikpapan, Kalimantan Timur 76114'
        ]);

        DB::table('cabang')->updateOrInsert(['id' => 2], [
            'nama_cabang' => 'BANDARA',
            'alamat' => 'Jl. Marsma R. Iswahyudi No.23B, Gn. Bahagia, Kecamatan Balikpapan Selatan, Kota Balikpapan, Kalimantan Timur 76114'
        ]);

        DB::table('cabang')->updateOrInsert(['id' => 3], [
            'nama_cabang' => 'PUSAT',
            'alamat' => 'Jl. Cakalang, RT.23/RW.No. 75, Manggar Baru, Kec. Balikpapan Tim., Kota Balikpapan, Kalimantan Timur 76117'
        ]);

        DB::table('cabang')->updateOrInsert(['id' => 4], [
            'nama_cabang' => 'APT Pranoto',
            'alamat' => 'Jl. Poros Samarinda - Bontang, Sungai Siring, Kec. Samarinda Utara, Kota Samarinda, Kalimantan Timur 75119'
        ]);
    }
}
