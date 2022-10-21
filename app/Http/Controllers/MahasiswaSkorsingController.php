<?php

namespace App\Http\Controllers;

use App\Models\JadwalMatkul;
use App\Models\Pengurus;
use App\Models\PenundaanSkorsing;
use App\Models\Sanksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MahasiswaSkorsingController extends Controller
{
    public function addPenundaan(Request $request)
    {
        if (!empty($request->id)) {
            session(['id_pelanggaran' => $request->id]);
        }
        
        $user = new User();
        $pengurus = new Pengurus();
        $sanksi = new Sanksi();
        $id_pelanggaran = session('id_pelanggaran');
        $profile = $user->getProfile(auth()->user());

        return view('mahasiswa.penundaan-skorsing', [
            'title' => 'Pengajuan Penundaan Skorsing',
            'user'  => $profile,
            'dosen' => $pengurus->getAllDosen(),
            'jum_sanksi' => $sanksi->getSkorsing($id_pelanggaran),
            'id_pelanggaran' => $id_pelanggaran,
        ]);
    }


    public function storePenundaan(Request $request)
    {
        $jadwal = new JadwalMatkul();
        $sanksi = new Sanksi();

        $now = Carbon::now()->toDateString();
        $id_pelanggaran = $request->id_pelanggaran;
        $tanggal = $request->tanggal;
        $tanggal_distinct = array_unique($request->tanggal);
        $jum_sanksi = $sanksi->getSkorsing($id_pelanggaran)->skorsing;
        $jum_hari = count($tanggal_distinct);


        foreach ($tanggal_distinct as $row) {
            if ($row <= $now) {
                return redirect()->back()->withInput()->with('message', 'JadwalTanggalSekarang');
            }
        }

        if ($jum_hari != $jum_sanksi) {
            return redirect()->back()->with('message', 'jumSkorsNotSame,' . $jum_sanksi);
        }

        $data = array();
        foreach ($tanggal as $key => $val) {
            $data[] = [
                'pelanggaran_mahasiswa_id' => $id_pelanggaran,
                'tanggal' => $tanggal[$key],
                'matkul' => $request->matkul[$key],
                'jam_mulai' => $request->jam_mulai[$key],
                'jam_selesai' => $request->jam_selesai[$key],
                'dosen' => $request->dosen[$key],
                'koordinator' => $request->koordinator[$key],
            ];
        }
        sort($tanggal_distinct);

        $cekLastPenundaan = PenundaanSkorsing::where('pelanggaran_mahasiswa_id', $id_pelanggaran)->orderBy('id','desc');
        $jadwal = JadwalMatkul::where('pelanggaran_mahasiswa_id', $id_pelanggaran )->orderBy('tanggal', 'asc');
        if($cekLastPenundaan->first() == null){
            $id_jadwal_lama = $jadwal->pluck('id')->implode(',');
            $tgl_jadwal_lama = array_unique($jadwal->distinct()->pluck('tanggal')->toArray());

            // dd(['lama'=> $tgl_jadwal_lama, 'baru'=> $tanggal_distinct, 'cek'=> $tanggal_distinct == $tgl_jadwal_lama, 'id_jadwal_lama' => $id_jadwal_lama]);
            if($tanggal_distinct == $tgl_jadwal_lama){
                return redirect()->back()->with('message', 'JadwalBukanTerbaru');
            } else {
                JadwalMatkul::insert($data);
                PenundaanSkorsing::create([
                    'pelanggaran_mahasiswa_id' => $id_pelanggaran,
                    'tgl_pengajuan' => Carbon::now()->toDateString(),
                    'keterangan' => $request->keterangan,
                    'jadwal_lama_id' => $id_jadwal_lama,
                ]);
            }
        } else {
            $id_jadwal_lama = explode(",",$cekLastPenundaan->first()->jadwal_lama_id);
            $tgl_jadwal_lama = $jadwal->whereIn('id', $id_jadwal_lama)->distinct()->pluck('tanggal')->toArray();

            if($tanggal_distinct == $tgl_jadwal_lama){
                return redirect()->back()->with('message', 'JadwalBukanTerbaru');
            } else {
                JadwalMatkul::insert($data);
                PenundaanSkorsing::create([
                    'pelanggaran_mahasiswa_id' => $id_pelanggaran,
                    'tgl_pengajuan' => Carbon::now()->toDateString(),
                    'keterangan' => $request->keterangan,
                    'jadwal_lama_id' => implode(',',$id_jadwal_lama),
                ]);
            }
        }
            

        return redirect()->route('mahasiswa.detail.pelanggaran')->with('message', 'PengajuanTundaBerhasil');
    }
}
