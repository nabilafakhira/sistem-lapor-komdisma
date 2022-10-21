<?php

namespace Database\Seeders;

use App\Models\KategoriPelanggaran;
use Illuminate\Database\Seeder;

class KategoriPelanggaranTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kategori_pelanggaran = [
            [
                "nama" => "Umum"
            ],
            [
                'nama' => "Khusus",
            ],
            [
                'nama' => "Ujian",
            ],
            [
                'nama' => "Lainnya",
            ],
        ];

        KategoriPelanggaran::insert($kategori_pelanggaran);
    }
}
