<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Prodi;
use App\Models\Sanksi;
use Illuminate\Http\Request;
use App\Models\JenisPelanggaran;
use App\Models\LaporPelanggaran;
use App\Models\LokasiPelanggaran;
use App\Models\PenundaanSkorsing;
use App\Models\KategoriPelanggaran;
use App\Models\PelanggaranMahasiswa;
use Illuminate\Support\Facades\Validator;

class VerifikasiPelanggaranController extends Controller
{
    public function show()
    {
        $user = new User();
        $pelanggaran = new PelanggaranMahasiswa();
        return view('komdisma.verifikasi-pelanggaran', [
            'title' => "Verifikasi Pelanggaran",
            'user'  => $user->getProfile(auth()->user()),
            'pelanggaran' => $pelanggaran->getPelanggaranVerifikasi(),
            'prodi' => Prodi::all(),
            'kategoripel' => KategoriPelanggaran::all(),
            'jenispel' => JenisPelanggaran::all(),
            'lokasi' => LokasiPelanggaran::all()
        ]);
    }

    public function getAllAjax(Request $request)
    {
        $pelanggaran = new PelanggaranMahasiswa();
        $filter = [
            'prodi' => $request->prodi,
            'kategori' => $request->kategori,
            'jenis' => $request->jenis,
            'lokasi' => $request->lokasi,

        ];
        $query = $pelanggaran->get_datatables_verifikasi($filter);
        $list = $query['data'];
        $data = array();
        foreach ($list as $item) {
            $row = array();
            $row[] = '';
            $row[] = $item->tanggal;
            $row[] = $item->nama_mahasiswa;
            $row[] = $item->prodi;
            $row[] = $item->nama_kategori;
            $row[] = $item->nama_jenis;
            $row[] = $item->nama_lokasi;
            $row[] = '<form action="'.route("detail.verifikasi").'" method="post">
                        '.csrf_field().'
                        <input type="hidden" value="'.$item->id_pelanggaran.'" name="id">
                        <button class="btn rounded-circle btn-outline-primary btn-sm" type="submit"><i
                                class="fas fa-pen"></i></button>
                    </form>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $pelanggaran->count_all_verifikasi(),
            "recordsFiltered" => $query['count_filtered'],
            "data" => $data,
            'filter' => $filter
        );
        // output to json format
        echo json_encode($output);
    }

    public function showDetail(Request $request)
    {
        if (!empty($request->id)) {
            session(['id_pelanggaran' => $request->id]);
        }
        $user = new User();
        $pelanggaran = new PelanggaranMahasiswa();
        $lapor = new LaporPelanggaran();
        $penundaan = new PenundaanSkorsing();
        $id_pelanggaran = session('id_pelanggaran');
        $detail = $pelanggaran->getDetail($id_pelanggaran);

        return view('komdisma.detail-verifikasi-pelanggaran', [
            'title' => "Detail Verifikasi Pelanggaran",
            'user'  => $user->getProfile(auth()->user()),
            'sanksi' => Sanksi::all(),
            'detail' => $detail,
            'pelanggaran' => $pelanggaran->getPelanggaran($detail->nim, $id_pelanggaran),
            'skorsing' => $pelanggaran->getPelanggaranSkorsing($detail->nim),
        ]);
    }

    public function update(Request $request)
    {
        $user = new User();
        $profile = $user->getProfile(auth()->user());

        $query = PelanggaranMahasiswa::find($request->id_pelanggaran);
        $query->inspektur = $profile->id;
        $query->tgl_verifikasi = Carbon::now()->toDateString();
        $query->sanksi_id = $request->id_sanksi;
        $query->save();

        return redirect()->route('show.verifikasi')->with('message', 'VerifikasiBerhasil');
    }

    public function updateNew(Request $request)
    {
        $user = new User();
        $profile = $user->getProfile(auth()->user());
        if ($request->lapor == 0) {
            $jum_lapor = NULL;
        } else {
            $jum_lapor = $request->lapor;
        }
        if ($request->skorsing == 0) {
            $jum_skorsing = NULL;
        } else {
            $jum_skorsing = $request->skorsing;
        }

        $cekSanksi = Sanksi::where('nama', $request->nama_sanksi)->first();

        if ($cekSanksi != null) {
            return redirect()->back()->with('message', 'VerifikasiGagal');
        }

        $querySanksi = new Sanksi();
        $querySanksi->nama = $request->nama_sanksi;
        $querySanksi->lapor = $jum_lapor;
        $querySanksi->skorsing = $jum_skorsing ;
        $querySanksi->drop_out = $request->drop_out;
        $querySanksi->save();
        if ($querySanksi) {
            $queryPelanggaran = PelanggaranMahasiswa::find($request->id_pelanggaran);
            $queryPelanggaran->sanksi_id = $querySanksi->id;
            $queryPelanggaran->inspektur = $profile->id;
            $queryPelanggaran->tgl_verifikasi = Carbon::now()->toDateString();
            $queryPelanggaran->save();

            return redirect()->route('show.verifikasi')->with('message', 'VerifikasiBerhasil');
        }
    }
}
