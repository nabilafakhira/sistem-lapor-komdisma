<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Models\JenisPelanggaran;
use App\Models\LaporPelanggaran;
use App\Models\PenundaanSkorsing;
use App\Models\PelanggaranMahasiswa;

class ProdiController extends Controller
{
    public function show()
    {
        $user = new User();
        return view('master-data.prodi', [
            'title' => 'Data Program Studi',
            'user' => $user->getProfile(auth()->user()),
            'prodi' => Prodi::orderBy('id', 'DESC')->get()
        ]);
    }

    public function store(Request $request)
    {
        $nama = $request->nama;
        $kode = $request->kode;
        foreach ($nama as $key => $val){
            $find = Prodi::where([['nama', $nama[$key]], ['kode', $kode[$key]]])->first();

            if($find == null){
                $prodi = new Prodi();
                $prodi->nama = $nama[$key];
                $prodi->kode = $kode[$key];
                $prodi->save();
            } else {
                continue;
            }
        }

        return redirect()->route('show.prodi')->with('message', 'TambahDataBerhasil');
    }


    public function update(Request $request)
    {
        $cek = Prodi::where([['id', '!=', $request->id],['nama', $request->nama], ['kode', $request->kode]])->first();

        if($cek != null) {
            return redirect()->route('show.prodi')->with('message', 'DataGagalEdit');
        }

        $update = Prodi::find($request->id);
        $update->nama = $request->nama;
        $update->kode = $request->kode;
        $update->save();

        return redirect()->route('show.prodi')->with('message', 'DataBerhasilEdit');
    }
}
