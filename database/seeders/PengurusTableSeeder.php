<?php

namespace Database\Seeders;

use App\Models\Pengurus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PengurusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pengurus = new Pengurus;
        $pengurus->id = '202103198710102001';
        $pengurus->user_id = 1;
        $pengurus->nama = "Gema Parasti Mindara S.Si., M.Kom.";
        $pengurus->ttd = "202103198710102001.jpg";
        $pengurus->save();
    }
}
