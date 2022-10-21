<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Sanksi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\JenisPelanggaran;
use App\Models\LaporPelanggaran;
use App\Models\LokasiPelanggaran;
use App\Models\PenundaanSkorsing;
use App\Models\KategoriPelanggaran;
use App\Models\PelanggaranMahasiswa;
use Illuminate\Support\Facades\Validator;

class TambahPelanggaranController extends Controller
{
    //fitur Lapor Pelanggaran
    public function show(Request $request)
    {
        $mahasiswa = new Mahasiswa();
        $user = new User();
        $pelanggaran = new PelanggaranMahasiswa();

        if (!empty($request->nim)) {
            session(['nim' => $request->nim]);
        }

        $nim = session('nim');

        $mahasiswa = $mahasiswa->getMahasiswa($nim);

        if (empty($mahasiswa)) {
            return redirect()->back()->with('message', 'NimNotFound');
        }

        // dd($data['skorsing']);
        return view('pengurus.tambah-pelanggaran', [

            'title' => 'Tambah Pelanggaran Mahasiswa',
            'user'  => $user->getProfile(auth()->user()),
            'mahasiswa' => $mahasiswa,
            'kategoripel' => KategoriPelanggaran::all(),
            'lokasi' => LokasiPelanggaran::all(),
            'pelanggaran' => $pelanggaran->getPelanggaran($nim),
            'skorsing' => $pelanggaran->getPelanggaranSkorsing($nim),
        ]);
    }

    public function validation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
            'jam' => 'required',
            'tingkat' => 'required',
            'lokasi' => 'required',
            'kategoriP' => 'required',
            'jenisP' => 'required',
            'keterangan' => 'required',
            'bukti_foto' => 'required|mimes:jpeg,png,jpg|max:500'
        ], [
            'tanggal.required' => 'Tanggal tidak boleh kosong',
            'jam.required' => 'Jam tidak boleh kosong',
            'tingkat.required' => 'Tingkat tidak boleh kosong',
            'lokasi.required' => 'Silahkan pilih lokasi',
            'kategoriP.required' => 'Silahkan pilih kategori pelanggaran',
            'jenisP.required' => 'Silahkan pilih jenis pelanggaran',
            'keterangan.required' => 'Keterangan tidak boleh kosong',
            'bukti_foto.required' => 'Bukti foto tidak boleh kosong',
            'bukti_foto.max' => 'Ukuran file lebih dari 500kb',
            'bukti_foto.mimes' => 'File yang dipilih bukan gambar',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        return response()->json(["status" => true, "message" => "validation-passed"]);
    }

    public function store(Request $request)
    {
        $user = new User();
        $file = $request->file('bukti_foto');
        $extension = '.'.$file->extension();
        $filename  = Carbon::now()->timestamp . $request->nim . $extension;
        $file->move(public_path("upload/tingkat$request->tingkat"), $filename);

        $data = [
            'nim' => $request->nim,
            'tingkat' => $request->tingkat,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'lokasi_id' => $request->lokasi,
            'jenis_pelanggaran_id' => $request->jenisP,
            'keterangan' => $request->keterangan,
            'pelapor' => $user->getProfile(auth()->user())->id,
            'bukti_foto' => $filename,
        ];

        PelanggaranMahasiswa::create($data);
        
        return redirect()->route('dashboard')->with('message', 'AddPelanggaranBerhasil');
    }

    
}
