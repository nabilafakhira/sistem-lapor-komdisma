<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PelanggaranMahasiswa;

class DashboardController extends Controller
{

    public function index()
    {  
        $user = new User();
        $pelanggaran = new PelanggaranMahasiswa();
        $profile = $user->getProfile(auth()->user());
        if($profile->role == "mahasiswa"){
            $countPelanggaran = $pelanggaran->countPelanggaranMahasiswa($profile->nim);
            $countStatus = $pelanggaran->countStatusMahasiswa($profile->nim);
            $cekLapor = $pelanggaran->cekLapor($profile->nim);
            if(in_array(TRUE, $cekLapor)){
                $alertLapor = TRUE;
            } else{
                $alertLapor = FALSE;
            }

            $cekSkorsing = $pelanggaran->cekSkorsing($profile->nim);
            if(in_array(TRUE, $cekSkorsing)){
                $alertSkors = TRUE;
            } else{
                $alertSkors = FALSE;
            }
            $kemajuanLapor = $pelanggaran->kemajuanLapor($profile->nim);
            // dd($kemajuanLapor);
            return view('dashboard', [
                'title' => 'Dashboard',
                'user' => $profile,
                'countPelanggaran' => $countPelanggaran,
                'countStatus' => $countStatus,
                'alertLapor' => $alertLapor,
                'alertSkors' => $alertSkors,
                'kemajuanLapor' => $kemajuanLapor
            ]);
        } else {
            return view('dashboard', [
                'title' => 'Dashboard',
                'user' => $profile,
                'countPelanggaran' => PelanggaranMahasiswa::all()->count(),
                'countStatus' => $pelanggaran->countStatus(),
                'countPelanggar' => PelanggaranMahasiswa::select('nim')->distinct()->count(),
            ]);
        }

        
        
    }

    
}
