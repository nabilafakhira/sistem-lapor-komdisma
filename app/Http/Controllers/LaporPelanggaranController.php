<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Models\LaporPelanggaran;
use App\Models\PenundaanSkorsing;
use App\Models\PelanggaranMahasiswa;

class LaporPelanggaranController extends Controller
{
    public function show()
    {
        $user = new User();
        $lapor = new LaporPelanggaran();
        $profile = $user->getProfile(auth()->user());

        return view('pengurus.laporan', [
            'title' => 'Laporan Pelanggaran Mahasiswa',
            'user'  => $profile,
            'prodi' => Prodi::all(),
            'lapor' => $lapor->getLaporMahasiswa($profile->id)
        ]);
    }

    public function update(Request $request)
    {
        $lapor = LaporPelanggaran::find($request->lapor_id);
        $lapor->keterangan = $request->keterangan;
        $lapor->status = 1;
        $lapor->save();

        return redirect()->back()->with('message', 'terimaLaporBerhasil');
    }

    public function getAllAjax(Request $request)
    {
        $user = new User();
        $id = $user->getProfile(auth()->user())->id;
        $lapor = new LaporPelanggaran();
        $query = $lapor->get_datatables($id, $request->prodi);
        $list = $query['data'];
        $data = array();
        foreach ($list as $item) {
            $row = array();
            $row[] = '';
            $row[] = $item->tgl_lapor;
            $row[] = $item->nama_mahasiswa;
            $row[] = $item->prodi;
            $row[] = $item->pelanggaran;
            $row[] = '<a href="#" data-toggle="modal" data-target="#terimaLapor"
            data-id="' . $item->id_lapor . '" class="btnUpdateLapor">
            <span class="btn rounded-circle btn-outline-primary btn-sm"><i
                    class="fas fa-check"></i></span>
        </a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $lapor->count_all($id),
            "recordsFiltered" => $query['count_filtered'],
            "data" => $data,
        );
        // output to json format
        echo json_encode($output);
    }
}
