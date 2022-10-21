<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Prodi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SuratKelakuanBaik;
use App\Models\PelanggaranMahasiswa;


class SuratKelakuanBaikController extends Controller
{
    public function show()
    {
        $user = new User();
        $surat = new SuratKelakuanBaik();
        $profile = $user->getProfile(auth()->user());
        // dd($surat->getAll());
        return view('komdisma.list-surat-kelakuan-baik', [
            'title' => 'Surat Berkelakuan Baik',
            'user'  => $profile,
            'prodi' => Prodi::all(),
            'pengajuan' => $surat->getAll(),
        ]);
    }

    public function add()
    {
        $pelanggaran = new PelanggaranMahasiswa();
        $user = new User();
        $surat = new SuratKelakuanBaik();
        $profile = $user->getProfile(auth()->user());
        $array = $pelanggaran->cekStatusMahasiswa($profile->nim);
        if((in_array(0,$array)) == TRUE){
            $statusPelanggaran = FALSE;
        } else {
            $statusPelanggaran = TRUE;
        }

        return view('mahasiswa.surat-kelakuan-baik', [
            'title' => 'Surat Berkelakuan Baik',
            'user'  => $profile,
            'statusPelanggaran' => $statusPelanggaran,
            'pengajuanTerakhir' => $surat->pengajuanTerakhir($profile->nim),
            'mahasiswa' => Mahasiswa::where('nim', $profile->nim)->with('prodi')->first(),
        ]);
    }

    public function store(Request $request){
        $user = new User();
        $profile = $user->getProfile(auth()->user());

        SuratKelakuanBaik::create([
            'nim' => $profile->nim,
            'tgl_pengajuan' => Carbon::now()->toDateString(),
            'keperluan' => $request->keperluan,
        ]);

        return redirect()->back()->with('message', 'PengajuanSuratBerhasil');

    }

    public function terimaPengajuan(Request $request){
        
        $user = new User();
        $query = SuratKelakuanBaik::find($request->id);
        $query->inspektur = $user->getProfile(auth()->user())->id;
        $query->tgl_berakhir = $request->tgl_berakhir;
        $query->status = 1;
        $query->save();

        return redirect()->back()->with('message', 'VerifikasiBerhasil'); 
    }

    public function tolakPengajuan(Request $request){
        $user = new User();
        $query = SuratKelakuanBaik::find($request->id);
        $query->inspektur = $user->getProfile(auth()->user())->id;
        $query->komentar = $request->komentar;
        $query->status = 0;
        $query->save();

        return redirect()->back()->with('message', 'VerifikasiBerhasil'); 
    }

    public function unduhSurat(Request $request){
        $user = new User();
        $surat = new SuratKelakuanBaik();
        $profile = $user->getProfile(auth()->user());

        $data = [
            'user'  => $profile,
            'prodi' => $request->prodi,
            'ttl' => $request->ttl,
            'alamat' => $request->alamat,
            'tgl_pengajuan' => $surat->pengajuanTerakhir($profile->nim)['tgl_pengajuan_lengkap'],
        ];
        
        $pdf = Pdf::loadView('templates.surat-kelakuan-baik', $data);
        
        return $pdf->download('Surat Keterangan Berkelakuan Baik.pdf');
    }
}
