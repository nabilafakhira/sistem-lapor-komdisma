<?php

namespace App\Http\Controllers;

use App\Models\JadwalMatkul;
use App\Models\Pengurus;
use App\Models\Sanksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MahasiswaJadwalController extends Controller
{
    public function add(Request $request)
    {
        if (!empty($request->id)) {
            session(['id_pelanggaran' => $request->id]);
        }
        $user = new User();
        $pengurus = new Pengurus();
        $sanksi = new Sanksi();
        $id_pelanggaran = session('id_pelanggaran');
        $profile = $user->getProfile(auth()->user());

        return view('mahasiswa.tambah-jadwal', [
            'title' => 'Tambah Jadwal',
            'user'  => $profile,
            'dosen' => $pengurus->getAllDosen(),
            'jum_sanksi' => $sanksi->getSkorsing($id_pelanggaran),
            'id_pelanggaran' => $id_pelanggaran,
        ]);
    }

    public function store(Request $request)
    {
        $jadwal = new JadwalMatkul();
        $sanksi = new Sanksi();

        $now = Carbon::now()->toDateString();
        $id_pelanggaran = $request->id_pelanggaran;
        $tanggal = $request->tanggal;
        $tanggal_distinct = array_unique($request->tanggal);
        $jum_sanksi = $sanksi->getSkorsing($id_pelanggaran)->skorsing;
        $jum_hari = count($tanggal_distinct);


        foreach($tanggal_distinct as $row){
            if($row <= $now){
                return redirect()->back()->withInput()->with('message', 'JadwalTanggalSekarang');
            } 
        }
        if ($jum_hari == $jum_sanksi) {
            $data = array();
            foreach ($tanggal as $key => $val){
                $data[] = [
                    'pelanggaran_mahasiswa_id' => $id_pelanggaran,
                    'tanggal' => $tanggal[$key],
                    'matkul' => $request->matkul[$key],
                    'jam_mulai' =>$request->jam_mulai[$key],
                    'jam_selesai' =>$request->jam_selesai[$key],
                    'dosen' =>$request->dosen[$key],
                    'koordinator' =>$request->koordinator[$key],
                ];
            }

            $query = JadwalMatkul::insert($data);
            if ($query) {
                return redirect()->route('mahasiswa.detail.pelanggaran')->with('message', 'AddJadwalBerhasil');
            }
        } else {
            return redirect()->back()->with('message', 'jumSkorsNotSame,' . $jum_sanksi);
        }
    }
}
