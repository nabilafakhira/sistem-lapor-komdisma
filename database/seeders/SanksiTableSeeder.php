<?php

namespace Database\Seeders;

use App\Models\Sanksi;
use Illuminate\Database\Seeder;

class SanksiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sanksi = [
            [
                "nama" => "Peringatan",
                "lapor" => null,
                "skorsing" => null
            ],
            [
                'nama' => "Pelanggaran 1",
                'lapor' => 7,
                "skorsing" => null
            ],
            [
                'nama' => "Pelanggaran 2",
                'lapor' => 14,
                'skorsing' => 3
            ],
            [
                'nama' => "Pelanggaran 3",
                'lapor' => 14,
                'skorsing' => 7
            ],
        ];

        Sanksi::insert($sanksi);
    }
}
