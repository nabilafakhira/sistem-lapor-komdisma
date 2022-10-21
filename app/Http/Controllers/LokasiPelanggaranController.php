<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\JenisPelanggaran;
use App\Models\LaporPelanggaran;
use App\Models\LokasiPelanggaran;
use App\Models\PenundaanSkorsing;
use App\Models\KategoriPelanggaran;
use App\Models\PelanggaranMahasiswa;

class LokasiPelanggaranController extends Controller
{
    public function show()
    {
        $user = new User();
        return view('master-data.lokasi-pelanggaran', [
            'title' => 'Data Lokasi Pelanggaran',
            'user' => $user->getProfile(auth()->user()),
            'lokasi' =>LokasiPelanggaran::orderBy('id', 'DESC')->get()
        ]);
    }

    public function store(Request $request)
    {
        $lokasi = $request->lokasi;
        foreach ($lokasi as $key => $val){
            $find = LokasiPelanggaran::where('nama', $lokasi[$key])->first();
            if($find == null){
                LokasiPelanggaran::create(['nama' => $lokasi[$key]]);
            } else {
                continue;
            }
        }

        return redirect()->route('show.lokasi.pelanggaran')->with('message', 'TambahDataBerhasil');
    }


    public function update(Request $request)
    {
        $cek = LokasiPelanggaran::where([['id', '!=', $request->id],['nama', $request->lokasi]])->first();

        if($cek != null) {
            return redirect()->route('show.lokasi.pelanggaran')->with('message', 'DataGagalEdit');
        }

        $update = LokasiPelanggaran::find($request->id);
        $update->nama = $request->lokasi;
        $update->save();

        return redirect()->route('show.lokasi.pelanggaran')->with('message', 'DataBerhasilEdit');
    }
}
