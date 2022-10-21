<?php

namespace App\Http\Controllers;

use App\Models\KategoriPelanggaran;
use App\Models\LokasiPelanggaran;
use App\Models\User;
use App\Models\PelanggaranMahasiswa;
use App\Models\Prodi;
use App\Models\Sanksi;
use Illuminate\Http\Request;

class ChartProdiController extends Controller
{

    public function show()
    {
        $user = new User();
        $pelanggaran = new PelanggaranMahasiswa();
        $profile = $user->getProfile(auth()->user());
        return view('pengurus.chart-prodi', [
            'title' => 'Grafik Prodi',
            'user' => $profile,
            'kategoripel' => KategoriPelanggaran::all(),
            'lokasi' => LokasiPelanggaran::all(),
            'sanksi' => Sanksi::all(),
            'prodi' => Prodi::all(),
        ]);
    }

    public function monthlyChartProdi(Request $request)
    {
        $pelanggaran = new PelanggaranMahasiswa();
        if($request->prodi1){
            $data = $pelanggaran->pelanggaranPerbulanProdi($request->prodi1);
        } else {
            $data = $pelanggaran->pelanggaranPerbulanProdi();
        }

        return response()->json($data);
    }

    public function chartByKategoriProdi(Request $request)
    {
        $pelanggaran = new PelanggaranMahasiswa();
        $data = empty($request->prodi2);
        if(!empty($request->jenisP) && !empty($request->prodi2)){
            $data = $pelanggaran->pelanggaranKategoriProdi($request->jenisP, $request->prodi2);
        } elseif((empty($request->jenisP) && !empty($request->prodi2))){
            $data = $pelanggaran->pelanggaranKategoriProdi(1, $request->prodi2);
        } elseif((!empty($request->jenisP) && empty($request->prodi2))){
            $data = $pelanggaran->pelanggaranKategoriProdi($request->jenisP, 1);
        } else {
            $data = $pelanggaran->pelanggaranKategoriProdi();
        }

        return response()->json($data);
    }

    public function chartByLokasiProdi(Request $request)
    {
        $pelanggaran = new PelanggaranMahasiswa();
        $data = empty($request->prodi3);
        if(!empty($request->lokasi) && !empty($request->prodi3)){
            $data = $pelanggaran->pelanggaranLokasiProdi($request->lokasi, $request->prodi3);
        } elseif((empty($request->lokasi) && !empty($request->prodi3))){
            $data = $pelanggaran->pelanggaranLokasiProdi(1, $request->prodi3);
        } elseif((!empty($request->lokasi) && empty($request->prodi3))){
            $data = $pelanggaran->pelanggaranLokasiProdi($request->lokasi, 1);
        } else {
            $data = $pelanggaran->pelanggaranLokasiProdi();
        }

        return response()->json($data);
    }

    public function chartBySanksiProdi(Request $request)
    {
        $pelanggaran = new PelanggaranMahasiswa();
        $data = empty($request->prodi3);
        if(!empty($request->sanksi) && !empty($request->prodi4)){
            $data = $pelanggaran->pelanggaranSanksiProdi($request->sanksi, $request->prodi4);
        } elseif((empty($request->sanksi) && !empty($request->prodi4))){
            $data = $pelanggaran->pelanggaranSanksiProdi(1, $request->prodi4);
        } elseif((!empty($request->sanksi) && empty($request->prodi4))){
            $data = $pelanggaran->pelanggaranSanksiProdi($request->sanksi, 1);
        } else {
            $data = $pelanggaran->pelanggaranSanksiProdi();
        }

        return response()->json($data);
    }
}
