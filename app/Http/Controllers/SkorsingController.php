<?php

namespace App\Http\Controllers;

use App\Models\JadwalMatkul;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Models\LaporPelanggaran;
use App\Models\Mahasiswa;
use App\Models\PenundaanSkorsing;
use App\Models\PelanggaranMahasiswa;

class SkorsingController extends Controller
{
    public function show()
    {
        $user = new User();
        $pelanggaran = new PelanggaranMahasiswa();

        return view('pengurus.list-skorsing', [
            'title' => 'Skorsing Mahasiswa',
            'user' => $user->getProfile(auth()->user()),
            'prodi' => Prodi::all(),
            'skorsing' => $pelanggaran->getAllSkorsing()
        ]);
    }

    public function showDetail(Request $request)
    {
        if (!empty($request->id)) {
            session(['id_pelanggaran' => $request->id]);
            session(['nim_mahasiswa' => $request->nim]);
        }

        $id_pelanggaran = session('id_pelanggaran');
        $nim_mahasiswa = session('nim_mahasiswa');
        $user = new User();
        $pelanggaran = new PelanggaranMahasiswa();
        $jadwal = new JadwalMatkul();
        
        return view('pengurus.detail-skorsing', [
            'title' => 'Skorsing Mahasiswa',
            'user' => $user->getProfile(auth()->user()),
            'jadwal' => $jadwal->getJadwal($id_pelanggaran),
            'mahasiswa' => Mahasiswa::where('nim', $nim_mahasiswa)->with('prodi')->first(),
            'status' => $pelanggaran->getStatusSkors(($id_pelanggaran))->status
        ]);
    }

    
}
