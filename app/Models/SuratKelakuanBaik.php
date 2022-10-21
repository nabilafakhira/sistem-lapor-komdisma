<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKelakuanBaik extends Model
{
    use HasFactory;

    protected $table = "surat_kelakuan_baik";
    protected $guarded = ['id'];
    public $timestamps = false;

    //func
    public function pengajuanTerakhir($nim){
        $pelanggaran = new PelanggaranMahasiswa();
        $date = Carbon::now()->toDateString();
        $query = SuratKelakuanBaik::selectRaw('tgl_pengajuan, tgl_berakhir, status, keperluan, komentar')->where('nim', $nim)->orderBy('id','DESC')->first();

        if(!empty($query->tgl_pengajuan)){
            if($query->status == 0){
                if(!empty($query->komentar)){
                    $data=[
                        'result' => TRUE,
                        'status' => $query->status,
                        'keperluan' => $query->keperluan,
                        'tgl_berakhir_lengkap' => $query->tgl_berakhir,
                        'tgl_pengajuan_lengkap' => $pelanggaran->formatTanggal($query->tgl_pengajuan),
                        'komentar' => $query->komentar,
                    ];
                } else {
                    $data=[
                        'result' => FALSE,
                        'status' => $query->status,
                        'keperluan' => $query->keperluan,
                        'tgl_berakhir_lengkap' => $query->tgl_berakhir,
                        'tgl_pengajuan_lengkap' => $pelanggaran->formatTanggal($query->tgl_pengajuan),
                        'komentar' => $query->komentar,
                    ];
                }
                
            } elseif($query->status == 1){
                if ($date > $query->tgl_berakhir){
                    $data=[
                        'result' => TRUE,
                        'status' => $query->status,
                        'keperluan' => $query->keperluan,
                        'tgl_berakhir_lengkap' => $pelanggaran->formatTanggal($query->tgl_berakhir),
                        'tgl_pengajuan_lengkap' => $pelanggaran->formatTanggal($query->tgl_pengajuan),
                        'komentar' => $query->komentar,
                    ];
                }else{
                    $data=[
                        'result' => FALSE,
                        'status' => $query->status,
                        'keperluan' => $query->keperluan,
                        'tgl_berakhir_lengkap' => $pelanggaran->formatTanggal($query->tgl_berakhir),
                        'tgl_pengajuan_lengkap' => $pelanggaran->formatTanggal($query->tgl_pengajuan),
                        'komentar' => $query->komentar,
                    ];
                }
            }
        } else {
            $data=[
                'result' => TRUE,
                'status' => 1,
                'keperluan' => NULL,
                'tgl_berakhir' => NULL,
                'tgl_pengajuan_lengkap' => NULL,
                'komentar' => NULL,
            ];
        }

        return $data;
    }

    public function getAll(){
        $query = SuratKelakuanBaik::selectRaw('surat_kelakuan_baik.id, mahasiswa.nim, mahasiswa.nama as nama_mahasiswa, prodi.kode as kode_prodi, prodi.nama as nama_prodi, DATE_FORMAT(tgl_pengajuan, "%d/%m/%Y") as tgl_pengajuan, inspektur, keperluan, DATE_FORMAT(tgl_berakhir, "%d/%m/%Y") as tgl_berakhir, status, surat_kelakuan_baik.komentar, pengurus.nama as nama_inspektur')
        ->join('mahasiswa', 'surat_kelakuan_baik.nim' ,'=', 'mahasiswa.nim')
        ->join('prodi', 'mahasiswa.prodi_id' ,'=', 'prodi.id')
        ->leftJoin('pengurus', 'surat_kelakuan_baik.inspektur', '=', 'pengurus.id')
        ->orderBy('surat_kelakuan_baik.id', 'DESC')->get();
        
        return $query;
    }   

}
