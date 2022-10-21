<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pengurus;
use App\Models\JadwalMatkul;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LaporPelanggaran;
use App\Models\PenundaanSkorsing;
use App\Models\PelanggaranMahasiswa;

class MahasiswaPelanggaranController extends Controller
{
    public function __construct()
    {
        $this->pelanggaran = new PelanggaranMahasiswa();
        $this->pelanggaran->insertTglSurat();
    }
    public function show()
    {
        $user = new User();
        $profile = $user->getProfile(auth()->user());
        return view('mahasiswa.list-pelanggaran', [
            'title' => 'Pelanggaran',
            'user'  => $profile,
            'pelanggaran' => $this->pelanggaran->getWhere($profile->nim),
        ]);
    }

    public function showDetail(Request $request)
    {
        if (!empty($request->id)) {
            session(['id_pelanggaran' => $request->id]);
        }
        $user = new User();
        $pelanggaran = new PelanggaranMahasiswa();
        $lapor = new LaporPelanggaran();
        $pengurus = new Pengurus();
        $jadwal = new JadwalMatkul();
        $penundaanSkorsing = new PenundaanSkorsing();
        $id_pelanggaran = session('id_pelanggaran');
        $detail = $pelanggaran->getDetail($id_pelanggaran);
        $profile = $user->getProfile(auth()->user());

        return view('mahasiswa.detail-pelanggaran', [
            'title' => 'Detail Pelanggaran',
            'user'  => $profile,
            'pelanggaran' => $detail,
            'canLapor' => $lapor->canLapor($id_pelanggaran),
            'cekPenundaan' => $penundaanSkorsing->cekPenundaan($id_pelanggaran),
            'statusSkorsing' => $pelanggaran->getStatusSkors($id_pelanggaran)->status,
            'lastLapor' => $lapor->lastLapor($id_pelanggaran),
            'dosen' => $pengurus->getAllDosen(),
            'lapor' => $lapor->getLapor($id_pelanggaran),
            'jadwal_skorsing' => $jadwal->getJadwal($id_pelanggaran),
            'jadwal_skorsing_baru' => $jadwal->getJadwalBaru($id_pelanggaran),
        ]);
    }

    public function storeLapor(Request $request){
        $lapor = new LaporPelanggaran();
        $lapor->penerima_lapor = $request->dosen;
        $lapor->pelanggaran_mahasiswa_id = $request->id_pelanggaran;
        $lapor->tanggal = Carbon::now()->toDateString();
        $lapor->save();
        
        return redirect()->back()->with('message', 'laporBerhasil');
    }

    public function chartJSON(){
        $user = new User();
        $pelanggaran = new PelanggaranMahasiswa();
        $data = [];
        $nim  = $user->getProfile(auth()->user())->nim;
        
        $data['kemajuanLapor'] = $pelanggaran->kemajuanLapor($nim);
        $data['pelanggaranTerakhir'] = $pelanggaran->pelanggaranTerakhirMahasiswa($nim);


        return response()->json($data);
        
    }

    public function unduhSurat(Request $request){
        $user = new User();
        $profile = $user->getProfile(auth()->user());

        $data = [
            'user'  => $profile,
            'prodi' => $request->prodi,
            'nama_inspektur' => $request->nama_inspektur,
            'tgl_surat_bebas' => $request->tgl_surat_bebas,
            'tgl_terakhir_lapor' => $request->tgl_terakhir_lapor,
            'jum_lapor' => $request->jum_lapor,
        ];

        
        // return view('templates.surat-bebas', $data);
        
        $pdf = Pdf::loadView('templates.surat-bebas', $data);
        
        return $pdf->download('Surat Keterangan Bebas Lapor.pdf');
    }
}
