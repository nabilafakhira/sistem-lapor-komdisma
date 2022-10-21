<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalMatkul extends Model
{
    use HasFactory;

    protected $table = "jadwal_matkul";
    protected $guarded = ['id'];
    public $timestamps = false;

    //function
    public function cekJadwal($id_pelanggaran)
    {
        return JadwalMatkul::where('pelanggaran_mahasiswa_id', $id_pelanggaran)->get();
    }

    public function getJadwal($id)
    {
        $pelanggaran = new PelanggaranMahasiswa();
        $query = JadwalMatkul::orderBy('tanggal', 'ASC')->where('pelanggaran_mahasiswa_id', $id)->get();

        foreach ($query as $row) {
            $row->nama_dosen = Pengurus::find($row->dosen)->nama;
            $row->nama_koordinator = Pengurus::find($row->koordinator)->nama;
            $row->tanggal_matkul = $pelanggaran->formatTanggal($row->tanggal);
            $row->hari = $pelanggaran->getHari($row->tanggal);
        }
        return $query;
    }

    public function getJadwalBaru($id)
    {
        $pelanggaran = new PelanggaranMahasiswa();
        $jadwal_lama = PenundaanSkorsing::where('pelanggaran_mahasiswa_id', $id)->orderBy('tgl_pengajuan', "desc")->first();
        if($jadwal_lama != null){
            $id_jadwal_lama = explode(',', $jadwal_lama->jadwal_lama_id);
            $query = JadwalMatkul::orderBy('tanggal', 'ASC')->where('pelanggaran_mahasiswa_id', $id)->whereNotIn('id', $id_jadwal_lama)->get();

            foreach ($query as $row) {
                $row->nama_dosen = Pengurus::find($row->dosen)->nama;
                $row->nama_koordinator = Pengurus::find($row->koordinator)->nama;
                $row->tanggal_matkul = $pelanggaran->formatTanggal($row->tanggal);
                $row->hari = $pelanggaran->getHari($row->tanggal);
            }
        } else {
            $query = null;
        }
        
        
        return $query;
    }

    public function getJadwalLama($id)
    {
        $pelanggaran = new PelanggaranMahasiswa();
        $jadwal_lama = PenundaanSkorsing::where('pelanggaran_mahasiswa_id', $id)->orderBy('tgl_pengajuan', "desc")->first();
        if($jadwal_lama != null){
            $id_jadwal_lama = explode(',', $jadwal_lama->jadwal_lama_id);
            $query = JadwalMatkul::orderBy('tanggal', 'ASC')->where('pelanggaran_mahasiswa_id', $id)->whereIn('id', $id_jadwal_lama)->get();

            foreach ($query as $row) {
                $row->nama_dosen = Pengurus::find($row->dosen)->nama;
                $row->nama_koordinator = Pengurus::find($row->koordinator)->nama;
                $row->tanggal_matkul = $pelanggaran->formatTanggal($row->tanggal);
                $row->hari = $pelanggaran->getHari($row->tanggal);
            }
        } else {
            $query = null;
        }
        
        
        return $query;
    }
}
