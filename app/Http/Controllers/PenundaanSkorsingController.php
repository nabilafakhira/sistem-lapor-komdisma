<?php

namespace App\Http\Controllers;

use App\Models\JadwalMatkul;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Models\LaporPelanggaran;
use App\Models\Mahasiswa;
use App\Models\PelanggaranMahasiswa;
use App\Models\PenundaanSkorsing;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class PenundaanSkorsingController extends Controller
{
    public function show()
    {
        $user = new User();
        $penundaan = new PenundaanSkorsing();
        return view('komdisma.list-penundaan-skorsing', [
            'title' => 'Penundaan Skorsing Mahasiswa',
            'user' => $user->getProfile(auth()->user()),
            'prodi' => Prodi::all(),
            'skorsing' => $penundaan->getAll()
        ]);
    }

    public function showDetail(Request $request)
    {
        if (!empty($request->id) and !empty($request->nim)) {
            session(['id_pelanggaran' => $request->id]);
            session(['nim_mahasiswa' => $request->nim]);
        }
        
        $id_pelanggaran = session('id_pelanggaran');
        $nim_mahasiswa = session('nim_mahasiswa');
        
        $user = new User();
        $jadwal = new JadwalMatkul();
        $penundaan = new PenundaanSkorsing();

        
        return view('komdisma.detail-penundaan-skorsing', [
            'title' => 'Detail Penundaan Skorsing',
            'user' => $user->getProfile(auth()->user()),
            'mahasiswa' => Mahasiswa::where('nim', $nim_mahasiswa)->with('prodi')->first(),
            'detail' => $penundaan->cekPenundaan($id_pelanggaran),
            'jadwal_lama' => $jadwal->getJadwalLama($id_pelanggaran),
            'jadwal_baru' => $jadwal->getJadwalBaru($id_pelanggaran),
        ]);
    }



    public function terimaPenundaan(Request $request){ 
        $user = new User();
        $query = PenundaanSkorsing::find($request->id);
        $id_jadwal_lama = explode(",",$query->jadwal_lama_id);
        $delete_jadwal_lama = JadwalMatkul::where('pelanggaran_mahasiswa_id', $query->pelanggaran_mahasiswa_id)->whereIn('id', $id_jadwal_lama)->delete();
        if($delete_jadwal_lama){
            $query->inspektur = $user->getProfile(auth()->user())->id;
            $query->jadwal_lama_id = null;
            $query->status = 1;
            $query->save();
        }
    
        return redirect()->route('show.penundaan.skorsing')->with('message', 'VerifikasiBerhasil');
    }

    public function tolakPenundaan(Request $request){
        $user = new User();
        $query = PenundaanSkorsing::find($request->id);
        $id_jadwal_lama = explode(",",$query->jadwal_lama_id);
        $delete_jadwal_baru = JadwalMatkul::where('pelanggaran_mahasiswa_id', $query->pelanggaran_mahasiswa_id)->whereNotIn('id', $id_jadwal_lama)->delete();
        if($delete_jadwal_baru){
            $query->inspektur = $user->getProfile(auth()->user())->id;
            $query->komentar = $request->komentar;
            $query->status = 0;
            $query->save();
        }

        return redirect()->route('show.penundaan.skorsing')->with('message', 'VerifikasiBerhasil');
    }
}
