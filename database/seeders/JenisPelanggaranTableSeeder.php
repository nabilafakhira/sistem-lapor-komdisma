<?php

namespace Database\Seeders;

use App\Models\JenisPelanggaran;
use Illuminate\Database\Seeder;

class JenisPelanggaranTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jenis_pelanggaran = [
            [
                "kategori_pelanggaran_id" => 1,
                "nama" => "Rambut"
            ],
            [
                "kategori_pelanggaran_id" => 1,
                "nama" => "Baju"
            ],
            [
                "kategori_pelanggaran_id" => 1,
                "nama" => "Celana"
            ],
            [
                "kategori_pelanggaran_id" => 2,
                "nama" => "Etika"
            ],
            [
                "kategori_pelanggaran_id" => 1,
                "nama" => "Makan & Minum"
            ],
            [
                "kategori_pelanggaran_id" => 3,
                "nama" => "Mencontek"
            ],
        ];

        JenisPelanggaran::insert($jenis_pelanggaran);
    }
}
