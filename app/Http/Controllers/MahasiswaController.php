<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Prodi;
use App\Models\Pengurus;
use App\Models\Mahasiswa;
use App\Imports\DosenImport;
use Illuminate\Http\Request;
use App\Imports\MahasiswaImport;
use App\Models\LaporPelanggaran;
use App\Models\PenundaanSkorsing;
use App\Models\PelanggaranMahasiswa;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaController extends Controller
{
    public function show()
    {
        $user = new User();

        return view('master-data.mahasiswa', [
            'title' => 'Data Mahasiswa',
            'user' => $user->getProfile(auth()->user()),
            'prodi' => Prodi::all()
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $user = new User();
        $mahasiswa = new Mahasiswa();
        $nim = $request->nim;
        $nama = $request->nama;
        $prodi = $request->prodi;
        foreach ($nama as $key => $val) {
            if (($user->checkUser($nim[$key]) == true) or ($mahasiswa->getMahasiswa($nim[$key]) != null)) {
                continue;
            } else {
                $data = [
                    'nim' => $nim[$key],
                    'nama' => $nama[$key],
                    'role' => "mahasiswa",
                    'prodi' => $prodi[$key],
                ];

                $user->regMahasiswa($data);
            }
        }

        return redirect()->route('show.mahasiswa')->with('message', 'TambahDataBerhasil');
    }

    public function import(Request $request)
    {
        Excel::import(new MahasiswaImport, $request->fileExcel);

        return redirect()->back()->with('message', 'TambahDataBerhasil');
    }

    public function getAllAjax(Request $request)
    {
        // dd($request->cariProdi);
        $mahasiswa = new Mahasiswa();
        $query = $mahasiswa->get_datatables($request->cariProdi);
        $list = $query['data'];
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $item->nim;
            $row[] = $item->nama;
            $row[] = $item->kode;
            $row[] = (!empty($item->kontak)) ? '<a href="https://wa.me/+62' . $item->kontak . '" class="text-success"><i class="fab fa-whatsapp fa-2x" ></i></a>' : '<div class="text-disabled"><i class="fab fa-whatsapp fa-2x" ></i></div>';
            $row[] = '<a href="'.route('reset.akun.mahasiswa', ['id' => $item->user_id]).'" class="btn rounded-circle btn-outline-success btn-sm mr-1 resetMahasiswa" data-toggle="tooltip" data-placement="top" title="Reset Akun"><i class="fas fa-redo-alt"></i></a>';
            $row[] = '<div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input check-item" name="id[]" value="' . $item->user_id . '" id="checkitem' . $item->user_id . '"><label class="custom-control-label" for="checkitem' . $item->user_id . '"></label></div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $mahasiswa->count_all(),
            "recordsFiltered" => $query['count_filtered'],
            "data" => $data,
        );
        // output to json format
        return response()->json($output);
    }
}
