<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Seeder;

class ProdiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prodi = [
            [
                'nama' => 'Komunikasi',
                'kode' => 'A-KMN'
            ],
            [
                'nama' => 'Ekowisata',
                'kode' => 'B-EKW'
            ],
            [
                'nama' => 'Manajemen Informatika',
                'kode' => 'C-INF'
            ],
            [
                'nama' => 'Teknik Komputer',
                'kode' => 'D-TEK'
            ],
            [
                'nama' => 'Supervisor Jaminan Mutu Pangan',
                'kode' => 'E-JMP'
            ],
            [
                'nama' => 'Manajemen Industri Jasa Makanan dan Gizi',
                'kode' => 'F-GZI'
            ],
            [
                'nama' => 'Teknologi Industri Benih',
                'kode' => 'G-TIB'
            ],
            [
                'nama' => 'Teknologi Produksi dan Manajemen Perikanan Budidaya',
                'kode' => 'H-IKN'
            ],
            [
                'nama' => 'Teknologi dan Manajemen Ternak',
                'kode' => 'I-TNK'
            ],
            [
                'nama' => 'Manajemen Agribisnis',
                'kode' => 'J-MAB'
            ],
            [
                'nama' => 'Manajemen Industri',
                'kode' => 'K-MNI'
            ],
            [
                'nama' => 'Analisis Kimia',
                'kode' => 'L-KIM'
            ],
            [
                'nama' => 'Teknik dan Manajemen Lingkungan',
                'kode' => 'M-LNK'
            ],
            [
                'nama' => 'Akuntansi',
                'kode' => 'N-AKN'
            ],
            [
                'nama' => 'Paramedik Veteriner',
                'kode' => 'P-PVT'
            ],
            [
                'nama' => 'Teknologi dan Manajemen Produksi Perkebunan',
                'kode' => 'T-TMP'
            ],
            [
                'nama' => 'Teknologi Produksi dan Pengembangan Masyarakat Pertanian',
                'kode' => 'W-PPP'
            ],
        ];

        
        Prodi::insert($prodi);
    }
}
