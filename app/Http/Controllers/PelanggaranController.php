<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Prodi;
use App\Models\Sanksi;
use App\Models\Pengurus;
use Illuminate\Http\Request;
use App\Models\JenisPelanggaran;
use App\Models\LaporPelanggaran;
use App\Models\LokasiPelanggaran;
use App\Models\PenundaanSkorsing;
use App\Models\SuratKelakuanBaik;
use Illuminate\Support\Facades\DB;
use App\Models\KategoriPelanggaran;
use App\Models\PelanggaranMahasiswa;

class PelanggaranController extends Controller
{
    public function show()
    {
        $user = new User();
        $pelanggaran = new PelanggaranMahasiswa();
        return view('komdisma.list-pelanggaran', [
            'title' => "Pelanggaran Mahasiswa",
            'user'  => $user->getProfile(auth()->user()),
            'pelanggaran' => $pelanggaran->getAll(),
            'prodi' => Prodi::all(),
            'kategoripel' => KategoriPelanggaran::all(),
            'jenispel' => JenisPelanggaran::all(),
            'lokasi' => LokasiPelanggaran::all(),
            'sanksi' => Sanksi::all()
        ]);
    }

    public function showDetail(Request $request)
    {
        if (!empty($request->id)) {
            session(['id_pelanggaran' => $request->id]);
        }

        $id = session('id_pelanggaran');
        $user = new User();
        $pelanggaran = new PelanggaranMahasiswa();
        $lapor = new LaporPelanggaran();
        $detail = $pelanggaran->getDetail($id);
        $profile = $user->getProfile(auth()->user());
        // dd($detail);
        return view('komdisma.detail-pelanggaran', [
            'title' => "Detail Pelanggaran",
            'user'  => $profile,
            'pelanggaran' => $detail,
            'lapor' => $lapor->getLapor($id)
        ]);
    }

    public function loloskan($id)
    {
        $query = PelanggaranMahasiswa::find($id);
        $query->tgl_surat_bebas = Carbon::now()->toDateString();
        $query->save();

        return redirect()->route('show.pelanggaran')->with('message', 'PelanggaranDiloloskan');
    }

    public function getCountAjax()
    {
        $user = new User();
        $countLapor = LaporPelanggaran::select('id')->where([['penerima_lapor', '=', $user->getProfile(auth()->user())->id], ['status', '=', 0]])->count();
        $countVerifikasi = PelanggaranMahasiswa::where('tgl_verifikasi', NULL)->count();
        $countPenundaan = PenundaanSkorsing::where([['komentar',NULL], ['status' ,0]])->count();
        $countSurat = SuratKelakuanBaik::where([['komentar', null], ['status' ,0]])->count();

        if($countLapor <= 99 && $countLapor > 0){
            $lapor = "<span class='badge badge-danger badge-counter'>$countLapor</span>";
        } else if($countLapor > 99){
            $lapor = "<span class='badge badge-danger badge-counter'>99+</span>";
        } else {
            $lapor ='';
            $countLapor = 0;
        }

        if($countVerifikasi <= 99 && $countVerifikasi > 0){
            $verifikasi = "<span class='badge badge-danger badge-counter'>$countVerifikasi</span>";
        } else if($countVerifikasi > 99){
            $verifikasi = "<span class='badge badge-danger badge-counter'>99+</span>";
        } else {
            $verifikasi ='';
            $countVerifikasi = 0;
        }

        if($countPenundaan <= 99 && $countPenundaan > 0){
            $penundaan = "<span class='badge badge-danger badge-counter'>$countPenundaan</span>";
        } else if($countPenundaan > 99){
            $penundaan = "<span class='badge badge-danger badge-counter'>99+</span>";
        } else {
            $penundaan = '';
            $countPenundaan = 0;
        }

        if($countSurat <= 99 && $countSurat > 0){
            $surat = "<span class='badge badge-danger badge-counter'>$countSurat</span>";
        } else if($countSurat > 99){
            $surat = "<span class='badge badge-danger badge-counter'>99+</span>";
        } else {
            $surat = '';
            $countSurat = 0;
        }

        $output = [
            "countLapor" => $lapor,
            "countVerifikasi" => $verifikasi,
            "countPenundaan" => $penundaan,
            'countSurat' => $surat,
            'lapor' => $countLapor,
            'verif' => $countVerifikasi,
            'penundaan' => $countPenundaan,
            'surat' => $countSurat,
        ];
        // output to json format
        return response()->json($output);

    }

    public function chartJSON(){
        $pelanggaran = new PelanggaranMahasiswa();
        $data = [];

        $data['countLastPelanggaran'] = $pelanggaran->pelanggaranTerakhir();
        $data['countStatus'] = $pelanggaran->countStatus();
        $data['pelanggaranProdi'] = $pelanggaran->pelanggaranProdi();


        return response()->json($data);
        
    }
}
