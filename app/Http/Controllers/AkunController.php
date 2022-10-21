<?php

namespace App\Http\Controllers;

use PDO;
use App\Models\User;
use App\Models\Pengurus;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\LaporPelanggaran;
use App\Models\PenundaanSkorsing;
use App\Models\PelanggaranMahasiswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AkunController extends Controller
{
    public function hapusAkun(Request $request)
    {
        $id = $request->id;
        if (!empty($id)) {
            $getPelanggaran = PelanggaranMahasiswa::select('bukti_foto', 'tingkat')
                ->join('mahasiswa', 'mahasiswa.nim', '=', 'pelanggaran_mahasiswa.nim')
                ->join('users', 'users.id', '=', 'mahasiswa.user_id')
                ->whereIn('users.id', $id)
                ->get();
            if ($getPelanggaran != null) {
                foreach ($getPelanggaran as $row) {
                    unlink(public_path("upload/tingkat$row->tingkat/$row->bukti_foto"));
                }
            }
            User::whereIn('id', $id)->delete();
            return redirect()->back()->with('message', 'HapusDataBerhasil');
        }

        return redirect()->back()->with('message', 'PilihData');
    }

    public function show()
    {
        $user = new User();
        return view('komdisma.list-akun', [
            'title' => 'Data Akun',
            'user' => $user->getProfile(auth()->user())
        ]);
    }

    public function getAllAjax(Request $request)
    {
        $user = new User();
        $query = $user->get_datatables($request->roleUser);
        $list = $query['data'];
        $data = array();
        foreach ($list as $item) {
            $strRole = ucwords(str_replace("-", " ", $item->role));
            $row = array();
            $row[] = '';
            $row[] = $item->username;
            $row[] = $item->email;
            $row[] = $strRole;
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $user->count_all(),
            "recordsFiltered" => $query['count_filtered'],
            "data" => $data,
        );
        // output to json format
        echo json_encode($output);
    }

    public function editPengurus()
    {
        $user = new User();
        return view('edit-akun', [
            'title' => 'Edit Akun',
            'user' => $user->getProfile(auth()->user()),
        ]);
    }

    public function validationPengurus(Request $request)
    {
        $findusername = User::where([['username', $request->username], ['id', '!=', $request->user_id]])->first();
        $findemail = User::where([['email', $request->email], ['id', '!=', $request->user_id]])->first();

        $error = array();

        if (auth()->user()->role == 'admin' || auth()->user()->role == "super-admin") {
            $cekttd = Pengurus::select('ttd')->where('user_id', $request->user_id)->first();
            if ($cekttd->ttd == null) {
                if ($request->file('ttd') == null) {
                    $error['ttd'] = 'TTD tidak boleh kosong';
                } else {
                    if ($request->file('ttd')->getSize() > 500000) {
                        $error['ttd'] = 'Ukuran file lebih dari 500kb';
                    }
                }
            }
        }


        if ($request->username == null) {
            $error['username'] = 'Username tidak boleh kosong';
        } else {
            if ($findusername != null) {
                $error['username'] = 'Username sudah terdaftar';
            }
        }

        if ($request->email == null) {
            $error['email'] = 'Email tidak boleh kosong';
        } else {
            if ($findemail != null) {
                $error['email'] = 'Email sudah terdaftar';
            }
        }

        if ($request->oldPassword) {
            $checkPassword = Hash::check($request->oldPassword, auth()->user()->password);
            if (!$checkPassword) {
                $error['oldPassword'] = 'Password lama yang anda masukkan salah';
            }
        }

        if ($request->newPassword) {
            if (strlen($request->newPassword) < 6) {
                $error['newPassword'] = 'Panjang password minimal 6 karakter';
            }
        }

        if ($error != null) {
            return response()->json(['error' => $error]);
        }


        return response()->json(["status" => true, "message" => "validation-passed"]);
    }

    public function updatePengurus(Request $request)
    {
        $cekttd = Pengurus::select('ttd')->where('user_id', $request->user_id)->first();

        $akun = User::find($request->user_id);
        $akun->username = $request->username;
        $akun->email = $request->email;
        if ($request->newPassword) {
            $akun->password = Hash::make($request->newPassword);
        }
        $akun->save();

        if ($cekttd->ttd == null && (auth()->user()->role == 'admin' || auth()->user()->role == "super-admin")) {
            if ($request->file('ttd')) {
                $file = $request->file('ttd');
                $extension = '.' . $file->extension();
                $filename  = $request->id . $extension;
                $file->move(public_path("img/ttd"), $filename);
                $pengurus = Pengurus::find($request->id);
                $pengurus->ttd = $filename;
                $pengurus->save();
            }
        }
        return redirect()->route('show.edit.akun.pengurus')->with('message', 'EditAkunBerhasil');
    }

    public function editMahasiswa()
    {
        $user = new User();
        return view('mahasiswa.edit-akun', [
            'title' => 'Edit Akun',
            'user' => $user->getProfile(auth()->user())
        ]);
    }

    public function validationMahasiswa(Request $request)
    {
        $findusername = User::where([['username', $request->username], ['id', '!=', $request->user_id]])->first();
        $findemail = User::where([['email', $request->email], ['id', '!=', $request->user_id]])->first();

        $error = array();
        if ($request->username == null) {
            $error['username'] = 'Username tidak boleh kosong';
        } else {
            if ($findusername != null) {
                $error['username'] = 'Username sudah terdaftar';
            }
        }

        if ($request->email == null) {
            $error['email'] = 'Email tidak boleh kosong';
        } else {
            if ($findemail != null) {
                $error['email'] = 'Email sudah terdaftar';
            }
        }

        if ($request->kontak == null) {
            $error['kontak'] = 'Kontak tidak boleh kosong';
        }

        if ($request->oldPassword) {
            $checkPassword = Hash::check($request->oldPassword, auth()->user()->password);
            if (!$checkPassword) {
                $error['oldPassword'] = 'Password lama yang anda masukkan salah';
            }
        }

        if ($request->newPassword) {
            if (strlen($request->newPassword) < 6) {
                $error['newPassword'] = 'Panjang password minimal 6 karakter';
            }
        }

        if ($error != null) {
            return response()->json(['error' => $error]);
        }


        return response()->json(["status" => true, "message" => "validation-passed"]);
    }

    public function updateMahasiswa(Request $request)
    {
        $akun = User::find($request->user_id);
        $akun->username = $request->username;
        $akun->email = $request->email;
        if ($request->newPassword) {
            $akun->password = Hash::make($request->newPassword);
        }
        $akun->save();

        Mahasiswa::where('user_id', $request->user_id)->update(['kontak' => $request->kontak]);

        return redirect()->route('show.edit.akun.mahasiswa')->with('message', 'EditAkunBerhasil');
    }

    //change role
    public function makeSuperAdmin(Request $request)
    {
        $akun = User::find($request->id);
        $akun->role = "super-admin";
        $akun->save();

        return redirect()->back()->with('message', 'UbahRoleBerhasil');
    }

    public function makeAdmin(Request $request)
    {
        $akun = User::find($request->id);
        $akun->role = "admin";
        $akun->save();

        return redirect()->back()->with('message', 'UbahRoleBerhasil');
    }

    public function makeDosen(Request $request)
    {
        $akun = User::find($request->id);
        $akun->role = "dosen";
        $akun->save();

        return redirect()->back()->with('message', 'UbahRoleBerhasil');
    }

    public function resetPasswordPengurus(Request $request)
    {
        $pengurus = Pengurus::select('id')->where('user_id', $request->id)->first();

        $akun = User::find($request->id);
        $akun->username = $pengurus->id;
        $akun->password = Hash::make($pengurus->id);
        $akun->save();

        return redirect()->back()->with('message', 'ResetAkunBerhasil');
    }

    public function resetPasswordMahasiswa(Request $request)
    {
        $mahasiswa = Mahasiswa::select('nim')->where('user_id', $request->id)->first();

        $akun = User::find($request->id);
        $akun->username = $mahasiswa->nim;
        $akun->password = Hash::make($mahasiswa->nim);
        $akun->save();

        return redirect()->back()->with('message', 'ResetAkunBerhasil');
    }
}
