<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pengurus;
use Illuminate\Http\Request;
use App\Imports\AkademikImport;
use App\Models\LaporPelanggaran;
use App\Models\PelanggaranMahasiswa;
use App\Models\PenundaanSkorsing;
use Maatwebsite\Excel\Facades\Excel;

class AkademikController extends Controller
{
    public function show()
    {
        $user = new User();
        $pengurus = new Pengurus();
        return view('master-data.akademik', [
            'title' => 'Data Akademik',
            'user' => $user->getProfile(auth()->user()),
            'akademik' => $pengurus->getPengurus('akademik')
        ]);
    }

    public function store(Request $request)
    {
        $user = new User();
        $pengurus = new Pengurus();
        $id = $request->id;
        $nama = $request->nama;
        foreach ($nama as $key => $val){
            if (($user->checkUser($id[$key]) == true) OR ($pengurus->checkPengurus($id[$key]) != null)) {
                continue;
            } else {
                $data = [
                    'id' => $id[$key],
                    'nama' => $nama[$key],
                    'role' => "akademik"
                ];
                $user->regPengurus($data);
            }
        }

        return redirect()->route('show.akademik')->with('message', 'TambahDataBerhasil');
    }

    public function import(Request $request)
    {
        Excel::import(new AkademikImport, $request->fileExcel);

        return redirect()->back()->with('message', 'TambahDataBerhasil');
    }
}
