<?php

namespace Database\Seeders;

use App\Models\LokasiPelanggaran;
use Illuminate\Database\Seeder;

class LokasiPelanggaranTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lokasi_pelanggaran = [
            [
                "nama" => "Gedung BS"
            ],
            [
                'nama' => "Gedung CA",
            ],
            [
                'nama' => "Gedung CB",
            ],
            [
                'nama' => "Gedung GG",
            ],
        ];

        LokasiPelanggaran::insert($lokasi_pelanggaran);
    }
}
