<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\JenisPelanggaran;
use App\Models\LaporPelanggaran;
use App\Models\PenundaanSkorsing;
use App\Models\KategoriPelanggaran;
use App\Models\PelanggaranMahasiswa;

class JenisPelanggaranController extends Controller
{
    public function getAllAjax(Request $request)
    {
        $query = JenisPelanggaran::where('kategori_pelanggaran_id', $request->id)->get();
        $data = '';

        foreach ($query as $row) {

            $data .= "<option value='$row->id'>$row->nama</option>";
        }
        return response()->json($data);
    }

    public function show()
    {
        $user = new User();
        return view('master-data.jenis-pelanggaran', [
            'title' => 'Data Jenis Pelanggaran',
            'user' => $user->getProfile(auth()->user()),
            'kategoripel' =>KategoriPelanggaran::all(),
            'jenispel' =>JenisPelanggaran::with('kategorip')->orderBy('id', 'DESC')->get()
        ]);
    }

    public function store(Request $request)
    {
        $nama = $request->jenis;
        $kategori = $request->kategori;
        foreach ($nama as $key => $val){
            $find = JenisPelanggaran::where([['nama', $nama[$key]], ['kategori_pelanggaran_id', $kategori[$key]]])->first();
            if($find == null){
                $jenispel = new JenisPelanggaran();
                $jenispel->nama = $nama[$key];
                $jenispel->kategori_pelanggaran_id = $kategori[$key];
                $jenispel->save();
            }
        }

        return redirect()->route('show.jenis.pelanggaran')->with('message', 'TambahDataBerhasil');
    }


    public function update(Request $request)
    {
        $cek = JenisPelanggaran::where([['id', '!=', $request->jenis_id],['nama', $request->jenisP], ['kategori_pelanggaran_id', $request->kategoriP]])->first();

        if($cek != null) {
            return redirect()->route('show.jenis.pelanggaran')->with('message', 'DataGagalEdit');
        }

        $update = JenisPelanggaran::find($request->jenis_id);
        $update->nama = $request->jenisP;
        $update->kategori_pelanggaran_id = $request->kategoriP;
        $update->save();

        return redirect()->route('show.jenis.pelanggaran')->with('message', 'DataBerhasilEdit');
    }
}
