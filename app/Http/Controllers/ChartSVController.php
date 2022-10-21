<?php

namespace App\Http\Controllers;

use App\Models\KategoriPelanggaran;
use App\Models\LokasiPelanggaran;
use App\Models\User;
use App\Models\PelanggaranMahasiswa;
use App\Models\Sanksi;
use Illuminate\Http\Request;

class ChartSvController extends Controller
{

    public function show()
    {
        $user = new User();
        $pelanggaran = new PelanggaranMahasiswa();
        $profile = $user->getProfile(auth()->user());
        return view('pengurus.chart-sv', [
            'title' => 'Grafik SV',
            'user' => $profile,
            'kategoripel' => KategoriPelanggaran::all(),
            'lokasi' => LokasiPelanggaran::all(),
            'sanksi' => Sanksi::all(),
        ]);
    }

    public function monthlyChart()
    {
        $pelanggaran = new PelanggaranMahasiswa();
        $data = $pelanggaran->pelanggaranPerbulan();

        return response()->json($data);
    }

    public function chartByKategori(Request $request)
    {
        $pelanggaran = new PelanggaranMahasiswa();
        if($request->jenisP){
            $data = $pelanggaran->pelanggaranKategori($request->jenisP);
        } else {
            $data = $pelanggaran->pelanggaranKategori();
        }

        return response()->json($data);
    }

    public function chartByLokasi(Request $request)
    {
        $pelanggaran = new PelanggaranMahasiswa();
        if($request->lokasi){
            $data = $pelanggaran->pelanggaranLokasi($request->lokasi);
        } else {
            $data = $pelanggaran->pelanggaranLokasi();
        }

        return response()->json($data);
    }

    public function chartBySanksi(Request $request)
    {
        $pelanggaran = new PelanggaranMahasiswa();
        if($request->sanksi){
            $data = $pelanggaran->pelanggaranSanksi($request->sanksi);
        } else {
            $data = $pelanggaran->pelanggaranSanksi();
        }

        return response()->json($data);
    }
}
