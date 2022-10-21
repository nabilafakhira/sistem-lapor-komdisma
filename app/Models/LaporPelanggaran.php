<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pengurus;
use App\Models\JadwalMatkul;
use Illuminate\Support\Facades\DB;
use App\Models\PelanggaranMahasiswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporPelanggaran extends Model
{
    use HasFactory;

    protected $table = "lapor_pelanggaran";
    protected $guarded = ['id'];
    public $timestamps = false;
    var $column_order = array(null, 'lapor_pelanggaran.tanggal', 'mahasiswa.nama', 'prodi.kode', 'pelanggaran_mahasiswa.keterangan'); //set column field database for datatable orderable
    var $column_search = array('lapor_pelanggaran.tanggal', 'mahasiswa.nama', 'prodi.kode', 'pelanggaran_mahasiswa.keterangan'); //set column field database for datatable searchable
    var $order = array('lapor_pelanggaran.tanggal' => 'desc'); // default order 

    //function
    public function getLapor($id){
        $query = LaporPelanggaran::selectRaw('lapor_pelanggaran.id as id_lapor, DATE_FORMAT(tanggal, "%d/%m/%Y") as tanggal, status, keterangan, penerima_lapor')->where('pelanggaran_mahasiswa_id', $id)->get();

        foreach ($query as $row) :
            $row->nama_penerima_lapor = Pengurus::find($row->penerima_lapor)->nama;
        endforeach;

        return $query;
    }

    public function getLaporMahasiswa($id_user){
        $where = [['penerima_lapor', '=', $id_user], ['status', '=', 0]];
        $query = LaporPelanggaran::selectRaw('lapor_pelanggaran.id as id_lapor, DATE_FORMAT(lapor_pelanggaran.tanggal, "%d/%m/%Y") as tgl_lapor, lapor_pelanggaran.keterangan as keterangan_lapor, prodi.kode as prodi, mahasiswa.nama as nama_mahasiswa, pelanggaran_mahasiswa.keterangan as pelanggaran')
        ->join('pelanggaran_mahasiswa', 'pelanggaran_mahasiswa.id', '=', 'lapor_pelanggaran.pelanggaran_mahasiswa_id')
        ->join('mahasiswa', 'mahasiswa.nim', '=', 'pelanggaran_mahasiswa.nim')
        ->join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
        ->where($where)->get();

        return $query;
    }

    function get_datatables($penerima, $prodi)
    {
        $where = [['penerima_lapor', '=', $penerima], ['status', '=', 0]];
        $query = LaporPelanggaran::selectRaw('lapor_pelanggaran.id as id_lapor, DATE_FORMAT(lapor_pelanggaran.tanggal, "%d/%m/%Y") as tgl_lapor, lapor_pelanggaran.keterangan as keterangan_lapor, prodi.kode as prodi, mahasiswa.nama as nama_mahasiswa, pelanggaran_mahasiswa.keterangan as pelanggaran')
        ->join('pelanggaran_mahasiswa', 'pelanggaran_mahasiswa.id', '=', 'lapor_pelanggaran.pelanggaran_mahasiswa_id')
        ->join('mahasiswa', 'mahasiswa.nim', '=', 'pelanggaran_mahasiswa.nim')
        ->join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
        ->where($where);
        if ($prodi != NULL) {
            $query->where('prodi.kode', $prodi);
        }

        if (@$_POST['search']['value']) { // if datatable send POST for search
            $query->where(function ($query1) {
                $query1->where(DB::raw("date_format(lapor_pelanggaran.tanggal, '%d/%m/%Y')"), 'like', '%' . $_POST['search']['value'] . '%');
                $query1->orWhere('mahasiswa.nama', 'like', '%' . $_POST['search']['value'] . '%');
                $query1->orWhere('prodi.kode', 'like', '%' . $_POST['search']['value'] . '%');
                $query1->orWhere('pelanggaran_mahasiswa.keterangan', 'like', '%' . $_POST['search']['value'] . '%');
            });
        }


        if (isset($_POST['order'])) { // here order processing
            $query->orderBy($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $query->orderBy(key($order), $order[key($order)]);
        }

        $countFiltered = $query->count();
        if (@$_POST['length'] != -1){
            $query->skip(@$_POST['start'])->take(@$_POST['length']);
        } 

        return ['data' => $query->get(), 'count_filtered' => $countFiltered];
    }
    function count_all($penerima)
    {
        $query = LaporPelanggaran::where([['penerima_lapor', '=', $penerima], ['status', '=', 0]]);
        return $query->count();
    }

    public function countLapor(){
        $user = new User();
        $where = [['penerima_lapor', '=', $user->getProfile(auth()->user())->id], ['status', '=', 0]];
        $query = LaporPelanggaran::select('id')->where($where)->count();

        return $query;
    }


    public function canLapor($id){
        $where = [['pelanggaran_mahasiswa_id', '=', $id], ['status', '=', 1]];
        $jum_lapor = LaporPelanggaran::select('id')->where($where)->count();

        $query_tgl_terakhir = LaporPelanggaran::selectRaw('max(tanggal) as tgl_terakhir')->where('pelanggaran_mahasiswa_id', $id)->first();
        if(!empty($query_tgl_terakhir)){
            $tgl_terakhir = $query_tgl_terakhir->tgl_terakhir;
        } else {
            $tgl_terakhir = null;
        }

        $pelanggaran = PelanggaranMahasiswa::selectRaw('tgl_verifikasi, tgl_surat_bebas, lapor, skorsing, drop_out')
        ->leftjoin('sanksi', 'sanksi.id', '=', 'pelanggaran_mahasiswa.sanksi_id')
        ->where('pelanggaran_mahasiswa.id',$id)->first();

        $jadwal = JadwalMatkul::where('pelanggaran_mahasiswa_id', $id)->get();

        $tanggal =Carbon::now()->toDateString();
        $didTanggalNow = ($tanggal == $tgl_terakhir);
        $didTanggalVerifikasi = (($pelanggaran->tgl_verifikasi) == $tanggal);
        $nameOfDay = date('l', strtotime($tanggal));

        if(empty($pelanggaran)){
            $data = [
                'hasil' => FALSE,
                'keterangan' => 'Pelanggaran tidak ada',
            ];
        } else {
            if ((!empty($pelanggaran->tgl_verifikasi)) AND (empty($pelanggaran->tgl_surat_bebas))){
                if(!empty($pelanggaran->lapor)){
                    if(($jum_lapor < ($pelanggaran->lapor)) ){
                        if(!empty($pelanggaran->skorsing)){
                            if($jadwal->isEmpty()){
                                    $data = [
                                    'hasil' => FALSE,
                                    'keterangan' => 'Jadwal belum lengkap',
                                ]; 
                            } else {
                                if(($didTanggalVerifikasi == FALSE) AND ($didTanggalNow == FALSE) AND ($nameOfDay != "Sunday" ) AND ($nameOfDay != "Saturday")){
                                    $data = [
                                        'hasil' => TRUE,
                                        'keterangan' => '',
                                    ];  
                                } else {
                                    if ($didTanggalVerifikasi == TRUE){
                                        $data = [
                                            'hasil' => FALSE,
                                            'keterangan' => 'Lapor dapat diajukan sehari setelah verifikasi',
                                        ];
                                    } elseif ($didTanggalNow == TRUE){
                                        $data = [
                                            'hasil' => FALSE,
                                            'keterangan' => 'Anda sudah melakukan lapor hari ini',
                                        ];
                                    } elseif (($nameOfDay != "Sunday" ) OR ($nameOfDay != "Saturday")){
                                        $data = [
                                            'hasil' => FALSE,
                                            'keterangan' => 'Tidak dapat lapor dihari sabtu dan minggu',
                                        ];
                                    }
                                }
                            }
                        } else {
                            if(($didTanggalVerifikasi == FALSE) AND ($didTanggalNow == FALSE) AND ($nameOfDay != "Sunday" ) AND ($nameOfDay != "Saturday")){
                                $data = [
                                    'hasil' => TRUE,
                                    'keterangan' => '',
                                ];  
                            } else {
                                if ($didTanggalVerifikasi == TRUE){
                                    $data = [
                                        'hasil' => FALSE,
                                        'keterangan' => 'Lapor dapat diajukan sehari setelah verifikasi',
                                    ];
                                } elseif ($didTanggalNow == TRUE){
                                    $data = [
                                        'hasil' => FALSE,
                                        'keterangan' => 'Anda sudah melakukan lapor hari ini',
                                    ];
                                } elseif (($nameOfDay != "Sunday" ) OR ($nameOfDay != "Saturday")){
                                    $data = [
                                        'hasil' => FALSE,
                                        'keterangan' => 'Tidak dapat lapor dihari sabtu dan minggu',
                                    ];
                                }
                            }
                        }
                    } else {
                        $data = [
                            'hasil' => FALSE,
                            'keterangan' => 'Lapor lengkap',
                        ];
                    }
                } else {
                    if(!empty($pelanggaran->skorsing)){
                        if($jadwal->isEmpty()){
                            $data = [
                                'hasil' => FALSE,
                                'keterangan' => 'Jadwal belum lengkap',
                            ];
                        } else {
                            $data = [
                                'hasil' => FALSE,
                                'keterangan' => 'Tidak ada lapor',
                            ];
                        }
                    } else {
                        $data = [
                            'hasil' => FALSE,
                            'keterangan' => 'Tidak ada lapor',
                        ];
                    }
                }
            } elseif (empty($pelanggaran->tgl_verifikasi)) {
                $data = [
                        'hasil' => FALSE,
                        'keterangan' => 'Belum diverifikasi',
                    ];
            } elseif (!empty($pelanggaran->tgl_surat_bebas)) {
                $data = [
                        'hasil' => FALSE,
                        'keterangan' => 'Lapor lengkap',
                    ];
            }
        }

        return $data;

    }

    public function lastLapor($id){
        $pelanggaran = new PelanggaranMahasiswa();
        $query= LaporPelanggaran::selectRaw('(max(tanggal)) as tanggal')->where('pelanggaran_mahasiswa_id', $id)->first();

        if(!empty($query->tanggal)){
            return $pelanggaran->formatTanggal($query->tanggal);
        } else {
            return NULL ;
        }

    }
}
