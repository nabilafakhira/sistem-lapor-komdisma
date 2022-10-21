<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pengurus;
use App\Imports\DosenImport;
use Illuminate\Http\Request;
use App\Models\LaporPelanggaran;
use App\Models\PenundaanSkorsing;
use App\Models\PelanggaranMahasiswa;
use Maatwebsite\Excel\Facades\Excel;

class DosenController extends Controller
{
    public function show()
    {
        $user = new User();
        return view('master-data.dosen', [
            'title' => 'Data Dosen',
            'user' => $user->getProfile(auth()->user())
        ]);
    }
    
    public function store(Request $request)
    {
        // dd($request->all());
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
                    'role' => "dosen"
                ];
                
                $user->regPengurus($data);
            }
        }

        return redirect()->route('show.dosen')->with('message', 'TambahDataBerhasil');
    }

    public function import(Request $request)
    {
        Excel::import(new DosenImport, $request->fileExcel);

        return redirect()->back()->with('message', 'TambahDataBerhasil');
    }

    public function getAllAjax(){
        $pengurus = new Pengurus();
        $user = new User();
        $role = ['dosen'];
        $query = $pengurus->get_datatables($role);
        $list = $query['data'];
        $data = array();
        foreach ($list as $item) {
            $row = array();
            $row[] = '';
            $row[] = $item->id;
            $row[] = $item->nama;
            if($user->getProfile(auth()->user())->role == "super-admin"){
                $row[] = '<a href="'.route('make.admin', ['id' => $item->user_id]).'" class="btn rounded-circle btn-outline-danger btn-sm mr-1 dosenKeadmin" data-toggle="tooltip" data-placement="top" title="Jadikan Admin"><i class="fas fa-level-up-alt px-15"></i></a>
                <a href="'.route('reset.akun.pengurus', ['id' => $item->user_id]).'" class="btn rounded-circle btn-outline-success btn-sm mr-1 resetAkun" data-toggle="tooltip" data-placement="top" title="Reset Akun"><i class="fas fa-redo-alt"></i></a>';
            } else {
                $row[] = '<a href="'.route('reset.akun.pengurus', ['id' => $item->user_id]).'" class="btn rounded-circle btn-outline-success btn-sm mr-1 resetAkun" data-toggle="tooltip" data-placement="top" title="Reset Akun"><i class="fas fa-redo-alt"></i></a>';
            }
            $row[] = '<div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input check-item" name="id[]" value="'.$item->user_id.'" id="checkitem'.$item->user_id.'"><label class="custom-control-label" for="checkitem'.$item->user_id.'"></label></div>';
            $data[] = $row;
        }
        $output = array(
                    "draw" => @$_POST['draw'],
                    "recordsTotal" => $pengurus->count_all($role),
                    "recordsFiltered" => $query['count_filtered'],
                    "data" => $data,
                );
        // output to json format
        return response()->json($output);
    }

}
