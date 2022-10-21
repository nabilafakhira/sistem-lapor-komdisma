<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Group;
use App\Models\Akademik;
use App\Models\Komdisma;
use App\Models\Pengurus;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->user = new User();
    }
    public function loginPengurus()
    {
        return view('auth.login');
    }

    public function loginMahasiswa()
    {
        return view('auth.login-mahasiswa');
    }

    public function postLoginPengurus(Request $request)
    {
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];
        $checkUser = $this->user->checkUser($request->username);
        $getRole = $this->user->getRole($request->username);
        if ($checkUser) {
            if ($getRole != "mahasiswa") {
                $attempt = Auth::attempt($credentials);
                if($attempt == false){
                    return redirect()->back()->with('message', 'loginGagal');
                }
                return redirect('/');
            }
        }
        return redirect()->back()->with('message', 'loginGagal');
    }

    public function postLoginMahasiswa(Request $request)
    {
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];
        $checkUser = $this->user->checkUser($request->username);
        $getRole = $this->user->getRole($request->username);
        if ($checkUser) {
            if ($getRole == "mahasiswa") {
                $attempt = Auth::attempt($credentials);
                if($attempt == false){
                    return redirect()->back()->with('message', 'loginGagal');
                }
                return redirect('/');
            }
        }
        return redirect()->back()->with('message', 'loginGagal');
    }

    public function logoutPengurus()
    {
        Auth::logout();
        return redirect()->route('login.pengurus');
    }

    public function logoutMahasiswa()
    {
        Auth::logout();
        return redirect()->route('login.mahasiswa');
    }
}
