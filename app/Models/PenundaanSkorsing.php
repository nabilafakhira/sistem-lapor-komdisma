<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenundaanSkorsing extends Model
{
    use HasFactory;

    protected $table = "penundaan_skorsing";
    protected $guarded = ['id'];
    public $timestamps = false;

    //function
    public function cekPenundaan($id_pelanggaran)
    {
        $pelanggaran = new PelanggaranMahasiswa();
        $query = PenundaanSkorsing::select('id', 'status', 'komentar', 'tgl_pengajuan', 'keterangan')
            ->where('pelanggaran_mahasiswa_id', $id_pelanggaran)
            ->orderBy('id', 'DESC')->first();
        if (!empty($query->tgl_pengajuan)) {
            $query->tgl_pengajuan_lengkap = $pelanggaran->formatTanggal($query->tgl_pengajuan);
        }
        return $query;
    }

    public function getAll()
    {

        $pelanggaran = new PelanggaranMahasiswa();
        $query = PenundaanSkorsing::selectRaw('penundaan_skorsing.id as id, DATE_FORMAT(tgl_pengajuan, "%d/%m/%Y") as tgl_pengajuan, DATE_FORMAT(tgl_pengajuan, "%Y-%m-%d") as tanggal, penundaan_skorsing.inspektur, penundaan_skorsing.keterangan, mahasiswa.nim, mahasiswa.nama, prodi.kode as kode_prodi, prodi.nama as nama_prodi, status, penundaan_skorsing.komentar, pelanggaran_mahasiswa_id')
            ->join('pelanggaran_mahasiswa', 'pelanggaran_mahasiswa.id', '=', 'penundaan_skorsing.pelanggaran_mahasiswa_id')
            ->join('mahasiswa', 'pelanggaran_mahasiswa.nim', '=', 'mahasiswa.nim')
            ->join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
            ->orderBy('penundaan_skorsing.id', 'DESC')->get();

        foreach ($query as $row) :
            if (!empty($row->inspektur)) {
                $row->nama_inspektur = Pengurus::find($row->inspektur)->nama;
            }
            $row->tgl_pengajuan_lengkap = $pelanggaran->formatTanggal($row->tanggal, true);
        endforeach;

        return $query;
    }

    public function countPengajuan()
    {
        $where = array('komentar' => NULL, 'status' => 0);
        $query = PenundaanSkorsing::where($where)->count();

        return $query;
    }
}
