<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use App\Models\KategoriPelanggaran;
use App\Models\LokasiPelanggaran;
use App\Models\PelanggaranMahasiswa;
use App\Models\Prodi;
use App\Models\Sanksi;
use App\Models\User;
use Illuminate\Http\Request;

class RekapanController extends Controller
{
    public function show()
    {
        $user = new User();
        $pelanggaran = new PelanggaranMahasiswa();
        return view('pengurus.rekapan', [
            'title' => 'Dashboard',
            'user' => $user->getProfile(auth()->user()),
            'data' => $pelanggaran->rekapan(),
            'prodi' => Prodi::all(),
            'jenis' => JenisPelanggaran::all(),
            'kategori' => KategoriPelanggaran::all(),
            'lokasi' => LokasiPelanggaran::all(),
            'sanksi' => Sanksi::all(),

        ]);
    }
}
