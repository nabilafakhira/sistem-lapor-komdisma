<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pengurus;
use Illuminate\Http\Request;
use App\Imports\KomdismaImport;
use App\Models\LaporPelanggaran;
use App\Models\PenundaanSkorsing;
use App\Models\PelanggaranMahasiswa;
use Maatwebsite\Excel\Facades\Excel;

class KomdismaController extends Controller
{
    public function show()
    {
        $user = new User();
        $pengurus = new Pengurus();
        
        return view('master-data.komdisma', [
            'title' => 'Data Komdisma',
            'user' => $user->getProfile(auth()->user()),
            'komdisma' => $pengurus->getPengurus('admin')
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
                    'role' => "admin"
                ];
                $user->regPengurus($data);
            }
        }

        return redirect()->route('show.komdisma')->with('message', 'TambahDataBerhasil');
    }

    public function import(Request $request)
    {
        Excel::import(new KomdismaImport, $request->fileExcel);

        return redirect()->back()->with('message', 'TambahDataBerhasil');
    }
}
