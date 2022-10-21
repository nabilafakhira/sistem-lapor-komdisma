<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;

class MahasiswaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pengurus = new Mahasiswa();
        $pengurus->nim = 'J3C118135';
        $pengurus->user_id = 2;
        $pengurus->nama = "Nabila Fakhiratunisa";
        $pengurus->prodi_id = 3;
        $pengurus->kontak = null;
        $pengurus->save();
    }
}
