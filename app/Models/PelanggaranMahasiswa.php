<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Sanksi;
use App\Models\Pengurus;
use App\Models\JenisPelanggaran;
use App\Models\LokasiPelanggaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PelanggaranMahasiswa extends Model
{
    use HasFactory;

    protected $table = "pelanggaran_mahasiswa";
    protected $guarded = ['id'];
    public $timestamps = false;
    var $column_order_verifikasi = array(null, 'tanggal', 'mahasiswa.nama', 'prodi.kode', 'kategori_pelanggaran.nama', 'jenis_pelanggaran.nama', 'lokasi_pelanggaran.nama'); //set column field database for datatable orderable
    var $column_search_verifikasi = array('tanggal', 'mahasiswa.nama', 'prodi.kode', 'kategori_pelanggaran.nama', 'jenis_pelanggaran.nama', 'lokasi_pelanggaran.nama'); //set column field database for datatable searchable
    var $order_verifikasi = array('pelanggaran_mahasiswa.id' => 'asc'); // default order 

    //relation
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim');
    }
    public function sanksi()
    {
        return $this->belongsTo(Sanksi::class, 'sanksi_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(LokasiPelanggaran::class, 'lokasi_id');
    }

    public function jenisp()
    {
        return $this->belongsTo(JenisPelanggaran::class, 'jenis_pelanggaran_id');
    }

    public function pelapor()
    {
        return $this->belongsTo(Pengurus::class, 'pelapor');
    }

    public function inspektur()
    {
        return $this->belongsTo(Pengurus::class, 'inspektur');
    }


    //function
    public function formatTanggal($tanggal, $cetak_hari = false)
    {
        $hari = array(
            1 =>    'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        );

        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $split       = explode('-', $tanggal);
        $tgl_indo = $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];

        if ($cetak_hari) {
            $num = date('N', strtotime($tanggal));
            return $hari[$num] . ', ' . $tgl_indo;
        }
        return $tgl_indo;
    }

    public function getHari($tanggal)
    {
        $hari = array(
            1 =>    'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        );

        $num = date('N', strtotime($tanggal));
        return $hari[$num];
    }

    public function getBulan($n)
    {
        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        return $bulan[(int)$n];
    }

    public function getStatusLapor($id)
    {
        $lapor = new LaporPelanggaran();
        $query = PelanggaranMahasiswa::selectRaw("max(lapor_pelanggaran.tanggal) as tgl_lapor, CASE
        WHEN sanksi.lapor IS NULL THEN 'Tidak Ada Lapor' 
        WHEN lapor_pelanggaran.pelanggaran_mahasiswa_id IS NULL AND sanksi.lapor IS NOT NULL THEN 'Belum Lapor' 
        WHEN (tgl_surat_bebas IS NULL) THEN 'Proses'
        WHEN (tgl_surat_bebas IS NOT NULL) THEN 'Selesai'
        END AS status")
            ->leftJoin('sanksi', 'sanksi.id', '=', 'pelanggaran_mahasiswa.sanksi_id')
            ->leftJoin('lapor_pelanggaran', 'lapor_pelanggaran.pelanggaran_mahasiswa_id', '=', 'pelanggaran_mahasiswa.id')
            ->where('pelanggaran_mahasiswa.id', $id)->first();

        if (!empty($query)) {
            if ($lapor->canLapor($id)['keterangan'] == "Lapor lengkap") {
                $query->status = "Selesai";
            } elseif ($lapor->canLapor($id)['keterangan'] == "Tidak ada lapor") {
                $query->status = "Tidak Ada Lapor";
            } else {
                $query->status = "Proses";
            }
        }

        return $query;
    }


    public function getStatusSkors($id)
    {
        $jadwal = new JadwalMatkul();
        $query = PelanggaranMahasiswa::selectRaw("max(jadwal_matkul.tanggal) as tgl_skors, pelanggaran_mahasiswa.id, sanksi.skorsing, tgl_surat_bebas,
        CASE
            WHEN sanksi.skorsing IS NULL THEN 'Tidak Ada Skors' 
            WHEN jadwal_matkul.pelanggaran_mahasiswa_id IS NULL AND sanksi.skorsing IS NOT NULL AND tgl_surat_bebas IS NULL THEN 'Belum mengisi jadwal'  
            WHEN (sanksi.skorsing = count(distinct jadwal_matkul.tanggal)  AND min(jadwal_matkul.tanggal) > CURRENT_DATE) AND tgl_surat_bebas IS NULL THEN 'Skorsing belum dimulai' 
            WHEN (sanksi.skorsing = count(distinct jadwal_matkul.tanggal) AND max(jadwal_matkul.tanggal) < CURRENT_DATE) THEN 'Selesai' 
            WHEN (tgl_surat_bebas IS NOT NULL) THEN 'Selesai'
        END AS status")
            ->leftJoin('sanksi', 'sanksi.id', '=', 'pelanggaran_mahasiswa.sanksi_id')
            ->leftJoin('jadwal_matkul', 'jadwal_matkul.pelanggaran_mahasiswa_id', '=', 'pelanggaran_mahasiswa.id')
            ->where('pelanggaran_mahasiswa.id', $id)->first();

        $date = Carbon::now()->toDateString();
        $array = $jadwal->select("tanggal")->where('pelanggaran_mahasiswa_id', $id)->get()->toArray();

        $find = PenundaanSkorsing::where('pelanggaran_mahasiswa_id',$id)->orderBy('id','desc')->first();

        if($find != null){
            if($find->status == 0 && $find->komentar == null)
            $query->status = "Mengajukan penundaan";
        }

        if ($query->status == NULL) {
            if (in_array(['tanggal' => $date], $array)) {
                $query->status = 'Sedang diskors';
            } else {
                $query->status = 'Skorsing belum dimulai';
            }
        }
        return $query;
    }

    public function getStatusPelanggaran($id)
    {
        $query = PelanggaranMahasiswa::select('tgl_verifikasi', 'tgl_surat_bebas', 'sanksi.drop_out as do')
            ->join('sanksi', 'sanksi.id', '=', 'pelanggaran_mahasiswa.sanksi_id')
            ->where('pelanggaran_mahasiswa.id', $id)->first();

        $lapor = $this->getStatusLapor($id)->status;
        $skors = $this->getStatusSkors($id)->status;

        if ((empty($query->tgl_verifikasi)) and (empty($query->tgl_surat_bebas))) {
            $status = 'Menunggu';
        } elseif ((!empty($query->tgl_verifikasi)) and (!empty($query->tgl_surat_bebas))) {
            if (($skors == "Tidak Ada Skors") or ($skors == "Selesai")) {
                if ($query->do == 0) {
                    $status = "Selesai";
                } else {
                    $status = "Drop Out";
                }
            } elseif (($skors == "Skorsing belum dimulai") or ($skors == "Mengajukan penundaan")) {
                $status = "Proses";
            } else {
                $status = $skors;
            }
        } else {
            if ($lapor == "Tidak Ada Lapor") {
                if ($skors == "Tidak Ada Skors") {
                    if ($query->do == 0) {
                        $status = "Selesai";
                    } else {
                        $status = "Drop Out";
                    }
                } elseif (($skors == "Belum mengisi jadwal") or ($skors == "Sedang diskors")) {
                    if (($query->do == 0) or ($query->do == 1)) {
                        $status = $skors;
                    }
                } elseif (($skors == "Skorsing belum dimulai") or ($skors == "Mengajukan penundaan")) {
                    if (($query->do == 0) or ($query->do == 1)) {
                        $status = "Proses";
                    }
                } elseif ($skors == "Selesai") {
                    if ($query->do == 0) {
                        $status = "Selesai";
                    } else {
                        $status = "Drop Out";
                    }
                }
            } elseif (($lapor == "Belum Lapor") or ($lapor == "Proses") or ($lapor == "Lapor belum diterima")) {
                if ($skors == "Tidak Ada Skors") {
                    if ($query->do == 0) {
                        $status = "Proses";
                    } else {
                        $status = "Proses";
                    }
                } elseif (($skors == "Belum mengisi jadwal") or $skors == "Sedang diskors") {
                    if (($query->do == 0) or ($query->do == 1)) {
                        $status = $skors;
                    }
                } elseif (($skors == "Skorsing belum dimulai") or ($skors == "Mengajukan penundaan")) {
                    if (($query->do == 0) or ($query->do == 1)) {
                        $status = "Proses";
                    }
                } elseif ($skors == "Selesai") {
                    if ($query->do == 0) {
                        $status = "Proses";
                    } else {
                        $status = "Proses";
                    }
                }
            } elseif ($lapor == "Selesai") {
                if ($skors == "Tidak Ada Skors") {
                    if ($query->do == 0) {
                        $status = "Selesai";
                    } else {
                        $status = "Drop Out";
                    }
                } elseif (($skors == "Belum mengisi jadwal") or $skors == "Sedang diskors") {
                    if (($query->do == 0) or ($query->do == 1)) {
                        $status = $skors;
                    }
                } elseif (($skors == "Skorsing belum dimulai") or ($skors == "Mengajukan penundaan")) {
                    if (($query->do == 0) or ($query->do == 1)) {
                        $status = "Proses";
                    }
                } elseif ($skors == "Selesai") {
                    if ($query->do == 0) {
                        $status = "Selesai";
                    } else {
                        $status = "Drop Out";
                    }
                }
            }
        }
        $data = [
            'status' => $status,
            'tgl_lapor' => $this->getStatusLapor($id)->tgl_lapor,
            'tgl_skors' => $this->getStatusSkors($id)->tgl_skors,
        ];
        return $data;
    }

    public function getPelanggaran($nim, $id = null)
    {
        $where = [['nim', '=', $nim], ['pelanggaran_mahasiswa.id', '!=', $id]];
        $query = PelanggaranMahasiswa::selectRaw("kategori_pelanggaran.nama as nama_kategori, jenis_pelanggaran.nama as nama_jenis, lokasi_pelanggaran.nama as nama_lokasi, DATE_FORMAT(tanggal, '%d/%m/%Y') as tanggal, pelanggaran_mahasiswa.id as pelanggaran_mahasiswa_id, keterangan, bukti_foto, jam, sanksi_id, sanksi.nama as nama_sanksi, tingkat")
            ->join('lokasi_pelanggaran', 'lokasi_pelanggaran.id', '=', 'pelanggaran_mahasiswa.lokasi_id')
            ->join('jenis_pelanggaran', 'jenis_pelanggaran.id', '=', 'pelanggaran_mahasiswa.jenis_pelanggaran_id')
            ->join('kategori_pelanggaran', 'kategori_pelanggaran.id', '=', 'jenis_pelanggaran.kategori_pelanggaran_id')
            ->leftJoin('sanksi', 'sanksi.id', '=', 'pelanggaran_mahasiswa.sanksi_id')
            ->where($where)
            ->orderBy('pelanggaran_mahasiswa_id', 'DESC')->get();

        foreach ($query as $row) :
            $row->status = $this->getStatusPelanggaran($row->pelanggaran_mahasiswa_id)['status'];
        endforeach;

        return $query;
    }

    public function getPelanggaranSkorsing($nim)
    {
        $where = [['sanksi.skorsing', '=', null], ['pelanggaran_mahasiswa.nim', '=', $nim]];
        $query = PelanggaranMahasiswa::selectRaw("pelanggaran_mahasiswa.id as pelanggaran_mahasiswa_id, mahasiswa.nim as nim_mahasiswa, mahasiswa.nama as nama_mahasiswa, prodi.nama as prodi, pelanggaran_mahasiswa.tanggal as tanggal_pelanggaran, sanksi.skorsing as jum_hari, min(jadwal_matkul.tanggal) as tgl_mulai, DATE_FORMAT(max(jadwal_matkul.tanggal), '%d/%m/%Y') as tgl_berakhir")
            ->join('mahasiswa', 'mahasiswa.nim', '=', 'pelanggaran_mahasiswa.nim')
            ->join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
            ->join('sanksi', 'sanksi.id', '=', 'pelanggaran_mahasiswa.sanksi_id')
            ->join('jadwal_matkul', 'jadwal_matkul.pelanggaran_mahasiswa_id', '=', 'pelanggaran_mahasiswa.id')
            ->where($where)
            ->groupBy('pelanggaran_mahasiswa.id')
            ->orderBy('pelanggaran_mahasiswa.id', 'DESC')->get();

        foreach ($query as $row) :
            $row->status = $this->getStatusSkors($row->pelanggaran_mahasiswa_id)->status;
        endforeach;
        return $query;
    }

    public function countVerifikasi()
    {
        $query = PelanggaranMahasiswa::where('tgl_verifikasi', NULL)->count();
        return $query;
    }

    public function getPelanggaranVerifikasi()
    {
        $query = PelanggaranMahasiswa::selectRaw('mahasiswa.nama as nama_mahasiswa, prodi.kode as prodi, kategori_pelanggaran.nama as nama_kategori, jenis_pelanggaran.nama as nama_jenis, lokasi_pelanggaran.nama as nama_lokasi, DATE_FORMAT(tanggal, "%d/%m/%Y") as tanggal, pelanggaran_mahasiswa.id as id_pelanggaran')
            ->join('mahasiswa', 'mahasiswa.nim', '=', 'pelanggaran_mahasiswa.nim')
            ->join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
            ->join('lokasi_pelanggaran', 'lokasi_pelanggaran.id', '=', 'pelanggaran_mahasiswa.lokasi_id')
            ->join('jenis_pelanggaran', 'jenis_pelanggaran.id', '=', 'pelanggaran_mahasiswa.jenis_pelanggaran_id')
            ->join('kategori_pelanggaran', 'kategori_pelanggaran.id', '=', 'jenis_pelanggaran.kategori_pelanggaran_id')
            ->where('tgl_verifikasi', NULL)
            ->orderBy('pelanggaran_mahasiswa.id', 'ASC')->get();

        return $query;
    }

    function get_datatables_verifikasi(array $filter)
    {
        $query = PelanggaranMahasiswa::selectRaw('mahasiswa.nama as nama_mahasiswa, prodi.kode as prodi, kategori_pelanggaran.nama as nama_kategori, jenis_pelanggaran.nama as nama_jenis, lokasi_pelanggaran.nama as nama_lokasi, DATE_FORMAT(tanggal, "%d/%m/%Y") as tanggal, pelanggaran_mahasiswa.id as id_pelanggaran')
            ->join('mahasiswa', 'mahasiswa.nim', '=', 'pelanggaran_mahasiswa.nim')
            ->join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
            ->join('lokasi_pelanggaran', 'lokasi_pelanggaran.id', '=', 'pelanggaran_mahasiswa.lokasi_id')
            ->join('jenis_pelanggaran', 'jenis_pelanggaran.id', '=', 'pelanggaran_mahasiswa.jenis_pelanggaran_id')
            ->join('kategori_pelanggaran', 'kategori_pelanggaran.id', '=', 'jenis_pelanggaran.kategori_pelanggaran_id')
            ->where('tgl_verifikasi', NULL);

        if ($filter['prodi'] != NULL) {
            $query->where('prodi.kode', $filter['prodi']);
        }
        if ($filter['kategori'] != NULL) {
            $query->where('kategori_pelanggaran.nama', $filter['kategori']);
        }
        if ($filter['jenis'] != NULL) {
            $query->where('jenis_pelanggaran.nama', $filter['jenis']);
        }
        if ($filter['lokasi'] != NULL) {
            $query->where('lokasi_pelanggaran.nama', $filter['lokasi']);
        }

        if (@$_POST['search']['value']) { // if datatable send POST for search
            $query->where(function ($query1) {
                $query1->where(DB::raw("date_format(tanggal, '%d/%m/%Y')"), 'like', '%' . $_POST['search']['value'] . '%');
                $query1->orWhere('mahasiswa.nama', 'like', '%' . $_POST['search']['value'] . '%');
                $query1->orWhere('prodi.kode', 'like', '%' . $_POST['search']['value'] . '%');
                $query1->orWhere('kategori_pelanggaran.nama', 'like', '%' . $_POST['search']['value'] . '%');
                $query1->orWhere('jenis_pelanggaran.nama', 'like', '%' . $_POST['search']['value'] . '%');
                $query1->orWhere('lokasi_pelanggaran.nama', 'like', '%' . $_POST['search']['value'] . '%');
            });
        }


        if (isset($_POST['order'])) { // here order processing
            $query->orderBy($this->column_order_verifikasi[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order_verifikasi)) {
            $order = $this->order_verifikasi;
            $query->orderBy(key($order), $order[key($order)]);
        }

        $countFiltered = $query->count();
        if (@$_POST['length'] != -1){
            $query->skip(@$_POST['start'])->take(@$_POST['length']);
        } 

        return ['data' => $query->get(), 'count_filtered' => $countFiltered];
    }
    function count_all_verifikasi ()
    {
        $query = PelanggaranMahasiswa::where('tgl_verifikasi', null);
        return $query->count();
    }

    public function getDetail($id)
    {
        $query = PelanggaranMahasiswa::selectRaw("kategori_pelanggaran.nama as nama_kategori, jenis_pelanggaran.nama as nama_jenis, lokasi_pelanggaran.nama as nama_lokasi, tanggal, pelanggaran_mahasiswa.id as id_pelanggaran, keterangan, bukti_foto, jam, pelapor, tingkat, sanksi_id, inspektur, sanksi.nama as nama_sanksi, lapor, drop_out, skorsing, tgl_surat_bebas, mahasiswa.nim, mahasiswa.nama as nama_mahasiswa, prodi.kode as kode_prodi, prodi.nama as nama_prodi")
            ->join('mahasiswa', 'mahasiswa.nim', '=', 'pelanggaran_mahasiswa.nim')
            ->join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
            ->join('lokasi_pelanggaran', 'lokasi_pelanggaran.id', '=', 'pelanggaran_mahasiswa.lokasi_id')
            ->join('jenis_pelanggaran', 'jenis_pelanggaran.id', '=', 'pelanggaran_mahasiswa.jenis_pelanggaran_id')
            ->join('kategori_pelanggaran', 'kategori_pelanggaran.id', '=', 'jenis_pelanggaran.kategori_pelanggaran_id')
            ->leftJoin('sanksi', 'sanksi.id', '=', 'pelanggaran_mahasiswa.sanksi_id')
            ->where('pelanggaran_mahasiswa.id', $id)->first();

        $query->nama_pelapor = Pengurus::find($query->pelapor)->nama;
        if (!empty($query->inspektur)) {
            $query->nama_inspektur = Pengurus::find($query->inspektur)->nama;
        }
        $query->status = $this->getStatusPelanggaran($query->id_pelanggaran)['status'];
        $query->tanggal_lengkap = $this->formatTanggal($query->tanggal);
        if (!empty($query->tgl_surat_bebas)) {
            $query->tgl_surat_bebas_lengkap = $this->formatTanggal($query->tgl_surat_bebas);
        } else {
            $query->tgl_surat_bebas_lengkap = $query->tgl_surat_bebas;
        }
        return $query;
    }

    public function getAll()
    {
        $query = PelanggaranMahasiswa::selectRaw("kategori_pelanggaran.nama as nama_kategori, jenis_pelanggaran.nama as nama_jenis, lokasi_pelanggaran.nama as nama_lokasi, DATE_FORMAT(tanggal, '%d/%m/%Y') as tanggal, pelanggaran_mahasiswa.id as id_pelanggaran, keterangan,  mahasiswa.nama as nama_mahasiswa, pelanggaran_mahasiswa.nim as nim, prodi.kode as prodi, sanksi.nama as nama_sanksi")
            ->join('mahasiswa', 'mahasiswa.nim', '=', 'pelanggaran_mahasiswa.nim')
            ->join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
            ->join('lokasi_pelanggaran', 'lokasi_pelanggaran.id', '=', 'pelanggaran_mahasiswa.lokasi_id')
            ->join('jenis_pelanggaran', 'jenis_pelanggaran.id', '=', 'pelanggaran_mahasiswa.jenis_pelanggaran_id')
            ->join('kategori_pelanggaran', 'kategori_pelanggaran.id', '=', 'jenis_pelanggaran.kategori_pelanggaran_id')
            ->leftJoin('sanksi', 'sanksi.id', '=', 'pelanggaran_mahasiswa.sanksi_id')
            ->orderBy('pelanggaran_mahasiswa.id', 'DESC')->get();

        foreach ($query as $row) :
            $row->status = $this->getStatusPelanggaran($row->id_pelanggaran)['status'];
        endforeach;

        return $query;
    }

    // function get_datatables($postData)
    // {
    //     $query = PelanggaranMahasiswa::selectRaw("kategori_pelanggaran.nama as nama_kategori, jenis_pelanggaran.nama as nama_jenis, lokasi_pelanggaran.nama as nama_lokasi, DATE_FORMAT(tanggal, '%d/%m/%Y') as tanggal, pelanggaran_mahasiswa.id as id_pelanggaran, keterangan,  mahasiswa.nama as nama_mahasiswa, pelanggaran_mahasiswa.nim as nim, prodi.kode as prodi, sanksi.nama as nama_sanksi")
    //         ->join('mahasiswa', 'mahasiswa.nim', '=', 'pelanggaran_mahasiswa.nim')
    //         ->join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
    //         ->join('lokasi_pelanggaran', 'lokasi_pelanggaran.id', '=', 'pelanggaran_mahasiswa.lokasi_id')
    //         ->join('jenis_pelanggaran', 'jenis_pelanggaran.id', '=', 'pelanggaran_mahasiswa.jenis_pelanggaran_id')
    //         ->join('kategori_pelanggaran', 'kategori_pelanggaran.id', '=', 'jenis_pelanggaran.kategori_pelanggaran_id')
    //         ->leftJoin('sanksi', 'sanksi.id', '=', 'pelanggaran_mahasiswa.sanksi_id')
    //         ->orderBy('pelanggaran_mahasiswa.id', 'DESC');
    //     if ($postData != NULL) {
    //         $strRole = strtolower(str_replace(" ", "-", $postData));
    //         $query->where('role', $strRole);
    //     }

    //     if (@$_POST['search']['value']) { // if datatable send POST for search
    //         $query->where(function ($query1) {
    //             $query1->where('username', 'like', '%' . $_POST['search']['value'] . '%');
    //             $query1->orWhere('email', 'like', '%' . $_POST['search']['value'] . '%');
    //             $query1->orWhere('role', 'like', '%' . strtolower(str_replace(" ", "-", $_POST['search']['value'])) . '%');
    //         });
    //     }


    //     if (isset($_POST['order'])) { // here order processing
    //         $query->orderBy($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    //     } else if (isset($this->order)) {
    //         $order = $this->order;
    //         $query->orderBy(key($order), $order[key($order)]);
    //     }

    //     $countFiltered = $query->count();
    //     if (@$_POST['length'] != -1){
    //         $query->skip(@$_POST['start'])->take(@$_POST['length']);
    //     } 


    //     return ['data' => $query->get(), 'count_filtered' => $countFiltered];
    // }
    // function count_all()
    // {
    //     $query = User::all();
    //     return $query->count();
    // }

    public function insertTglSurat()
    {
        $query = $this->getAll();

        foreach ($query as $row) :
            $statusLapor = $this->getStatusLapor($row->id_pelanggaran)->status;
            $statusPelanggaran = $this->getStatusPelanggaran($row->id_pelanggaran)['status'];
            if (($statusLapor == "Selesai") and (empty($row->tgl_surat_bebas))) {
                if (($statusPelanggaran == "Selesai") or ($statusPelanggaran == "Drop Out")) {
                    $pelanggaran = PelanggaranMahasiswa::find($row->id_pelanggaran);
                    $pelanggaran->tgl_surat_bebas = Carbon::now()->toDateString();
                    $pelanggaran->save();
                }
            }
        endforeach;
    }

    public function getWhere($nim)
    {
        $query = PelanggaranMahasiswa::selectRaw("kategori_pelanggaran.nama as nama_kategori, jenis_pelanggaran.nama as nama_jenis, lokasi_pelanggaran.nama as nama_lokasi, DATE_FORMAT(tanggal, '%d/%m/%Y') as tanggal, pelanggaran_mahasiswa.id as id_pelanggaran, keterangan, bukti_foto, jam")
            ->join('lokasi_pelanggaran', 'lokasi_pelanggaran.id', '=', 'pelanggaran_mahasiswa.lokasi_id')
            ->join('jenis_pelanggaran', 'jenis_pelanggaran.id', '=', 'pelanggaran_mahasiswa.jenis_pelanggaran_id')
            ->join('kategori_pelanggaran', 'kategori_pelanggaran.id', '=', 'jenis_pelanggaran.kategori_pelanggaran_id')
            ->where('nim', $nim)
            ->orderBy('pelanggaran_mahasiswa.id', 'DESC')->get();

        foreach ($query as $row) :
            $row->status = $this->getStatusPelanggaran($row->id_pelanggaran)['status'];
        endforeach;

        return $query;
    }

    public function getAllSkorsing()
    {
        $query = PelanggaranMahasiswa::selectRaw("pelanggaran_mahasiswa.id as id_pelanggaran, mahasiswa.nim as nim_mahasiswa, mahasiswa.nama as nama_mahasiswa, prodi.kode as prodi, pelanggaran_mahasiswa.tanggal as tanggal_pelanggaran, sanksi.skorsing as jum_hari, min(jadwal_matkul.tanggal) as tgl_mulai, DATE_FORMAT(max(jadwal_matkul.tanggal), '%d/%m/%Y') as tgl_berakhir")
            ->join('mahasiswa', 'mahasiswa.nim', '=', 'pelanggaran_mahasiswa.nim')
            ->join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
            ->leftJoin('sanksi', 'sanksi.id', '=', 'pelanggaran_mahasiswa.sanksi_id')
            ->leftJoin('jadwal_matkul', 'jadwal_matkul.pelanggaran_mahasiswa_id', '=', 'pelanggaran_mahasiswa.id')
            ->where('sanksi.skorsing', '!=', NULL)
            ->groupBy('pelanggaran_mahasiswa.id')
            ->orderBy('pelanggaran_mahasiswa.id', 'DESC')->get();

        foreach ($query as $row) :
            $row->status = $this->getStatusSkors($row->id_pelanggaran)->status;
        endforeach;

        return $query;
    }

    public function getSkorsingMahasiswa($id)
    {
        $query = PelanggaranMahasiswa::selectRaw("sanksi.skorsing as jum_hari, jadwal_matkul.tanggal as tgl_matkul, mahasiswa.nama as nama_mahasiswa, mahasiswa.nim as nim_mahasiswa, prodi.kode as prodi, pelanggaran_mahasiswa.id as id_pelanggaran, pelanggaran_mahasiswa.tanggal as tanggal_pelanggaran, jam_mulai, jam_selesai, dosen, koordinator, matkul")
        ->join('mahasiswa', 'mahasiswa.nim', '=', 'pelanggaran_mahasiswa.nim')
        ->join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
        ->join('sanksi', 'sanksi.id', '=', 'pelanggaran_mahasiswa.sanksi_id')
        ->join('jadwal_matkul', 'jadwal_matkul.pelanggaran_mahasiswa_id', '=', 'pelanggaran_mahasiswa.id')
        ->orderBy('jadwal_matkul.tanggal', 'ASC')
        ->where('pelanggaran_mahasiswa.id', $id)->get();

        foreach ($query as $row){
            $row->nama_dosen = Pengurus::find($row->dosen)->nama;
            $row->nama_koor = Pengurus::find($row->koordinator)->nama;
            $row->hari = $this->getHari($row->tgl_matkul);
            $row->tanggal_matkul = $this->formatTanggal($row->tgl_matkul);
        }

        $data = [
            'mahasiswa' => [$query[0]->nama_mahasiswa, $query[0]->nim_mahasiswa, $query[0]->prodi, $this->getStatusSkors($id)->status],
            'skorsing' => $query,
        ];

        return $data;
    }

    
    public function cekStatusMahasiswa($nim){
        $query = PelanggaranMahasiswa::select('id')->where('nim', $nim)->get();

        $result = array();
        if(!empty($query)){
            foreach ($query as $row) :
                if(($this->getStatusPelanggaran($row->id)['status']) != 'Selesai'){
                    $result[] = 0 ;
                } else {
                    $result[] = 1;
                }
                
            endforeach;
        } else {
            $result[] = 1;
        }

        return $result;
    }

    public function rekapan(){

        $query = PelanggaranMahasiswa::selectRaw('pelanggaran_mahasiswa.id as id_pelanggaran, DATE_FORMAT(tanggal, "%d/%m/%Y") as tanggal, mahasiswa.nama, mahasiswa.nim, prodi.kode as prodi, kategori_pelanggaran.nama as nama_kategori, jenis_pelanggaran.nama as nama_jenis, sanksi.nama as nama_sanksi, lokasi_pelanggaran.nama as nama_lokasi, pelapor, inspektur, DATE_FORMAT(tgl_surat_bebas, "%d/%m/%Y") as tgl_surat_bebas')
        ->join('mahasiswa', 'mahasiswa.nim','=','pelanggaran_mahasiswa.nim')
        ->join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
        ->join('jenis_pelanggaran', 'jenis_pelanggaran.id', '=', 'pelanggaran_mahasiswa.jenis_pelanggaran_id')
        ->join('kategori_pelanggaran', 'kategori_pelanggaran.id', '=', 'jenis_pelanggaran.kategori_pelanggaran_id')
        ->join('lokasi_pelanggaran', 'lokasi_pelanggaran.id', '=', 'pelanggaran_mahasiswa.lokasi_id')
        ->leftJoin('sanksi', 'sanksi.id', '=', 'pelanggaran_mahasiswa.sanksi_id')
        ->orderBy('pelanggaran_mahasiswa.tanggal', 'DESC')->get();

        foreach ($query as $row) :
            if(!empty($row->inspektur)){
                $row->nama_inspektur = Pengurus::find($row->inspektur)->nama;
            } else {
                $row->nama_inspektur = NULL;
            }
            $row->nama_pelapor = Pengurus::find($row->pelapor)->nama;
            $row->status = $this->getStatusPelanggaran($row->id_pelanggaran)['status'];
        endforeach;

        return $query;
    }


    public function pelanggaranTerakhir(){
        $query = DB::select("SELECT DATE_FORMAT(tanggal, '%c') AS bulan, COUNT(*) AS jumlah_bulanan
        FROM pelanggaran_mahasiswa WHERE tanggal >= CURDATE() - INTERVAL 6 MONTH GROUP BY YEAR(tanggal),MONTH(tanggal);");

        $bulan = array();
        $jumlah = array();
        foreach ($query as $row):
            $bulan[] = $this->getBulan($row->bulan);
            $jumlah[] = $row->jumlah_bulanan;
        endforeach;

        $data=[$bulan, $jumlah];

        return $data;
        
    }

    public function countStatus()
    {
        $query = PelanggaranMahasiswa::select('id')->get();
        $jumlahSelesai = 0;
        $jumlahNotSelesai = 0;
        foreach ($query as $row):
            if( $this->getStatusPelanggaran($row->id)['status'] == "Selesai"){
                $jumlahSelesai= $jumlahSelesai + 1 ;
            }elseif ($this->getStatusPelanggaran($row->id)['status'] != "Selesai"){
                $jumlahNotSelesai = $jumlahNotSelesai + 1; 
            }
        endforeach;

        $data = [$jumlahNotSelesai,$jumlahSelesai];

        return $data;
    }

    public function pelanggaranProdi()
    {
        $query = PelanggaranMahasiswa::selectRaw('COUNT(*) AS jumlah, prodi.kode as prodi')
        ->join('mahasiswa', 'mahasiswa.nim', '=', 'pelanggaran_mahasiswa.nim')
        ->join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
        ->groupBy('prodi.kode')->get();

        if(!empty($query)){
            $nama_prodi = array();
            $jumlah = array();
            foreach ($query as $row):
                $nama_prodi[] = $row->prodi;
                $jumlah[] = $row->jumlah;
            endforeach;

            $data=[$nama_prodi, $jumlah];
        } else {
            $data=[NULL, NULL];
        }

        return $data;
    }

    public function countPelanggaranMahasiswa($nim){
        $jumlah = PelanggaranMahasiswa::where('nim', $nim)->count();
        if(empty($jumlah)){
            $jumlah = 0;
        }
        return $jumlah;
    }

    public function countStatusMahasiswa($nim){
        $query = PelanggaranMahasiswa::where('nim', $nim)->get();

        $jumlahSelesai = 0;
        $jumlahNotSelesai = 0;
        foreach ($query as $row):
            if( $this->getStatusPelanggaran($row->id)['status'] == "Selesai"){
                $jumlahSelesai= $jumlahSelesai + 1 ;
            }elseif ($this->getStatusPelanggaran($row->id)['status'] != "Selesai"){
                $jumlahNotSelesai = $jumlahNotSelesai + 1; 
            }
        endforeach;

        $data = [$jumlahNotSelesai, $jumlahSelesai];

        return $data;
    }

    public function pelanggaranTerakhirMahasiswa($nim){
        $query = DB::select("SELECT DATE_FORMAT(tanggal, '%c') AS bulan, COUNT(*) AS jumlah_bulanan
        FROM pelanggaran_mahasiswa WHERE tanggal >= CURDATE() - INTERVAL 12 MONTH AND nim = '$nim' GROUP BY YEAR(tanggal),MONTH(tanggal)");

        $bulan = array();
        $jumlah = array();
        foreach ($query as $row):
            $bulan[] = $this->getBulan($row->bulan);
            $jumlah[] = $row->jumlah_bulanan;
        endforeach;

        $data=[$bulan, $jumlah];
        return $data;
        
    }

    public function cekLapor($nim){
        $where = [['nim', $nim], ['sanksi.lapor', '!=',NULL]];
        $query = PelanggaranMahasiswa::select('lapor', 'pelanggaran_mahasiswa.id as id_pelanggaran')
        ->join('sanksi', 'sanksi.id','=','pelanggaran_mahasiswa.sanksi_id')
        ->where($where)->get();
        $date = Carbon::now('Asia/Jakarta')->toDateString();
        $nameOfDay = date('l', strtotime($date));
        $result = array();
        foreach ($query as $row):
            if((($this->getStatusLapor($row->id_pelanggaran)->status) != "Selesai") AND (($this->getStatusSkors($row->id_pelanggaran)->status) != "Sedang diskors")){
                $lapor = new LaporPelanggaran();
                if (($lapor->canLapor($row->id_pelanggaran)['hasil']) == TRUE){
                    if (($nameOfDay != "Sunday" ) AND ($nameOfDay != "Saturday")){
                        $result[] = TRUE;
                    } else{
                        $result[] = FALSE;
                    }
                } else{
                    $result[] = FALSE;
                }
                
            } 
        endforeach;

        return $result;
    }

    public function cekSkorsing($nim){
        $where = [['nim',$nim], ['sanksi.lapor','!=',NULL]];
        $query = PelanggaranMahasiswa::select('pelanggaran_mahasiswa.id as id_pelanggaran')
        ->join('sanksi', 'sanksi.id','=','pelanggaran_mahasiswa.sanksi_id')
        ->where($where)->get();
        $result = array();
        foreach ($query as $row):
            if((($this->getStatusSkors($row->id_pelanggaran)->status) == "Sedang diskors")){
                    $result[] = TRUE;
                } else{
                    $result[] = FALSE;
                }
        endforeach;

        return $result;
    }

    public function kemajuanLapor($nim){
        $where1 = [['nim',$nim], ['sanksi.lapor', '!=', NULL]];
        $query1 = PelanggaranMahasiswa::select('sanksi.lapor', 'pelanggaran_mahasiswa.id', 'tanggal')
        ->join('sanksi', 'sanksi.id','=','pelanggaran_mahasiswa.sanksi_id')
        ->where($where1)->get();

        $data = array();
        foreach ($query1 as $row):
            $temp = array();
            if (($this->getStatusLapor($row->id)->status) != "Selesai"){
                $temp [] = $this->formatTanggal($row->tanggal);
                $jum_sanksi = $row->lapor;
                $where2 = [['nim',$nim], ['lapor_pelanggaran.status' , 1], ['lapor_pelanggaran.pelanggaran_mahasiswa_id',$row->id]];
                $jumlah = PelanggaranMahasiswa::join('lapor_pelanggaran','lapor_pelanggaran.pelanggaran_mahasiswa_id', '=', 'pelanggaran_mahasiswa.id')->where($where2)->count();

                if($jumlah){
                    $temp [] = round($jumlah * 100 / $jum_sanksi);
                } else {
                    $temp [] = 0;
                }
            } else {
                continue;
            }
            $data [] = $temp;
        endforeach;

        return $data;
    }

    //chart
    public function pelanggaranPerbulan(){
        $query = DB::select("SELECT DATE_FORMAT(tanggal, '%c') AS bulan, COUNT(*) AS jumlah_bulanan
        FROM pelanggaran_mahasiswa WHERE tanggal >= CURDATE() - INTERVAL 12 MONTH GROUP BY YEAR(tanggal),MONTH(tanggal);");

        if(!empty($query)){
            $bulan = array();
            $jumlah = array();
            foreach ($query as $row):
                $bulan[] = $this->getBulan($row->bulan);
                $jumlah[] = $row->jumlah_bulanan;
            endforeach;

            $data=[
                'bulan' => $bulan, 
                'jumlah' => $jumlah
            ];
        } else {

            $data=[
                'bulan' => null, 
                'jumlah' => null
            ];
        }

        return $data;
        
    }

    public function pelanggaranKategori($id = 1){
        $query =  DB::select("SELECT DATE_FORMAT(tanggal, '%c') AS bulan, COUNT(*) AS jumlah_bulanan, kategori_pelanggaran.nama as nama_kategori, jenis_pelanggaran.nama as nama_jenis FROM pelanggaran_mahasiswa join jenis_pelanggaran on jenis_pelanggaran.id = pelanggaran_mahasiswa.jenis_pelanggaran_id join kategori_pelanggaran on kategori_pelanggaran.id=jenis_pelanggaran.kategori_pelanggaran_id WHERE tanggal >= CURDATE() - INTERVAL 12 MONTH AND jenis_pelanggaran_id = '$id' GROUP BY YEAR(tanggal),MONTH(tanggal);");

        if(!empty($query)){
            $bulan = array();
            $jumlah = array();
            $nama_kategori = $query[0]->nama_kategori;
            $nama_jenis = $query[0]->nama_jenis;
            foreach ($query as $row):
                $bulan[] = $this->getBulan($row->bulan);
                $jumlah[] = $row->jumlah_bulanan;
            endforeach;

            $data=[
                'bulan' => $bulan, 
                'jumlah' => $jumlah,
                'kategori' => $nama_kategori,
                'jenis' => $nama_jenis
            ];
        } else {
            $query2 = JenisPelanggaran::select('jenis_pelanggaran.nama AS nama_jenis', 'kategori_pelanggaran.nama AS nama_kategori')
            ->join('kategori_pelanggaran', 'kategori_pelanggaran.id', '=', 'jenis_pelanggaran.kategori_pelanggaran_id')->where('jenis_pelanggaran.id', $id)->first();
            $nama_kategori = $query2->nama_kategori;
            $nama_jenis = $query2->nama_jenis;
            $data=[
                'bulan' => null, 
                'jumlah' => null,
                'kategori' => $nama_kategori,
                'jenis' => $nama_jenis
            ];
        }
        return $data;
    }

    public function pelanggaranLokasi($id = 1){
        $query = DB::select("SELECT DATE_FORMAT(tanggal, '%c') AS bulan, COUNT(*) AS jumlah_bulanan, lokasi_pelanggaran.nama as nama_lokasi FROM pelanggaran_mahasiswa join lokasi_pelanggaran on lokasi_pelanggaran.id=pelanggaran_mahasiswa.lokasi_id WHERE tanggal >= CURDATE() - INTERVAL 12 MONTH AND lokasi_id = '$id' GROUP BY YEAR(tanggal),MONTH(tanggal);");

        if(!empty($query)){
            $bulan = array();
            $jumlah = array();
            $nama_lokasi = $query[0]->nama_lokasi;
            foreach ($query as $row):
                $bulan[] = $this->getBulan($row->bulan);
                $jumlah[] = $row->jumlah_bulanan;
            endforeach;

            $data=[
                'bulan' => $bulan, 
                'jumlah' => $jumlah, 
                'lokasi' => $nama_lokasi
            ];

        } else {
            $lokasi= LokasiPelanggaran::where('id',$id)->first();
            $nama_lokasi = $lokasi->nama;
            $data=[
                'bulan' => null, 
                'jumlah' => null, 
                'lokasi' => $nama_lokasi
            ];
        }
        return $data;
        
    }

    public function pelanggaranSanksi($id = 1){
        $query = DB::select("SELECT DATE_FORMAT(tanggal, '%c') AS bulan, COUNT(*) AS jumlah_bulanan, sanksi.nama as nama_sanksi FROM pelanggaran_mahasiswa join sanksi on sanksi.id=pelanggaran_mahasiswa.sanksi_id WHERE tanggal >= CURDATE() - INTERVAL 12 MONTH AND sanksi_id = '$id' GROUP BY YEAR(tanggal),MONTH(tanggal);");

        if(!empty($query)){
            $bulan = array();
            $jumlah = array();
            $nama_sanksi = $query[0]->nama_sanksi;
            foreach ($query as $row):
                $bulan[] = $this->getBulan($row->bulan);
                $jumlah[] = $row->jumlah_bulanan;
            endforeach;

            $data=[
                'bulan' => $bulan, 
                'jumlah' => $jumlah, 
                'sanksi' => $nama_sanksi
            ];

        } else {
            $sanksi= Sanksi::where('id', $id)->first();
            $nama_sanksi = $sanksi->nama;
            $data=[
                'bulan' => null, 
                'jumlah' => null, 
                'sanksi' => $nama_sanksi
            ];
        }
        return $data;
        
    }

    public function pelanggaranPerbulanProdi($prodi = 1){
        $query = DB::select("SELECT DATE_FORMAT(tanggal, '%c') AS bulan, COUNT(*) AS jumlah_bulanan, prodi.nama as nama_prodi FROM pelanggaran_mahasiswa join mahasiswa on mahasiswa.nim=pelanggaran_mahasiswa.nim join prodi on prodi.id=mahasiswa.prodi_id WHERE tanggal >= CURDATE() - INTERVAL 12 MONTH AND prodi.id = '$prodi' GROUP BY YEAR(tanggal),MONTH(tanggal);");

        if(!empty($query)){
            $bulan = array();
            $jumlah = array();
            $nama_prodi = $query[0]->nama_prodi;
            foreach ($query as $row):
                $bulan[] = $this->getBulan($row->bulan);
                $jumlah[] = $row->jumlah_bulanan;
            endforeach;

            $data=[
                'bulan' => $bulan, 
                'jumlah' => $jumlah, 
                'prodi' => $nama_prodi
            ];
        } else {
            $prodi = Prodi::where('id', $prodi)->first();
            $data=[
                'bulan' => null, 
                'jumlah' => null, 
                'prodi' => $prodi->nama
            ];
        }

        return $data;
        
    }

    public function pelanggaranKategoriProdi($id = 1, $prodi = 1){
        $query = DB::select("SELECT DATE_FORMAT(tanggal, '%c') AS bulan, COUNT(*) AS jumlah_bulanan, prodi.nama as nama_prodi, kategori_pelanggaran.nama as nama_kategori, jenis_pelanggaran.nama as nama_jenis FROM pelanggaran_mahasiswa join jenis_pelanggaran on jenis_pelanggaran.id=pelanggaran_mahasiswa.jenis_pelanggaran_id join kategori_pelanggaran on kategori_pelanggaran.id=jenis_pelanggaran.kategori_pelanggaran_id join mahasiswa on mahasiswa.nim=pelanggaran_mahasiswa.nim join prodi on prodi.id=mahasiswa.prodi_id WHERE tanggal >= CURDATE() - INTERVAL 12 MONTH AND jenis_pelanggaran_id= '$id' AND prodi.id = '$prodi' GROUP BY YEAR(tanggal),MONTH(tanggal);");

        if(!empty($query)){
            $bulan = array();
            $jumlah = array();
            $nama_kategori = $query[0]->nama_kategori;
            $nama_jenis = $query[0]->nama_jenis;
            $nama_prodi = $query[0]->nama_prodi;
            foreach ($query as $row):
                $bulan[] = $this->getBulan($row->bulan);
                $jumlah[] = $row->jumlah_bulanan;
            endforeach;

            $data=[
                'bulan' => $bulan, 
                'jumlah' => $jumlah,
                'kategori' => $nama_kategori,
                'jenis' => $nama_jenis,
                'prodi' => $nama_prodi
            ];
        } else {
            $prodi= Prodi::where('id', $prodi)->first();
            $query2 = JenisPelanggaran::select('jenis_pelanggaran.nama AS nama_jenis', 'kategori_pelanggaran.nama AS nama_kategori')
            ->join('kategori_pelanggaran', 'kategori_pelanggaran.id', '=', 'jenis_pelanggaran.kategori_pelanggaran_id')->where('jenis_pelanggaran.id', $id)->first();
            $nama_kategori = $query2->nama_kategori;
            $nama_jenis = $query2->nama_jenis;
            $data=[
                'bulan' => null, 
                'jumlah' => null,
                'kategori' => $nama_kategori,
                'jenis' => $nama_jenis,
                'prodi' => $prodi->nama
            ];
        }
        
        return $data;
        
    }

    public function pelanggaranLokasiProdi($id = 1, $prodi = 1){
        $query = DB::select("SELECT DATE_FORMAT(tanggal, '%c') AS bulan, COUNT(*) AS jumlah_bulanan, prodi.nama as nama_prodi, lokasi_pelanggaran.nama as nama_lokasi FROM pelanggaran_mahasiswa join lokasi_pelanggaran on lokasi_pelanggaran.id=pelanggaran_mahasiswa.lokasi_id join mahasiswa on mahasiswa.nim=pelanggaran_mahasiswa.nim join prodi on prodi.id=mahasiswa.prodi_id WHERE tanggal >= CURDATE() - INTERVAL 12 MONTH AND lokasi_pelanggaran.id = '$id' AND prodi.id = '$prodi' GROUP BY YEAR(tanggal),MONTH(tanggal);");

        if(!empty($query)){
            $bulan = array();
            $jumlah = array();
            $nama_lokasi = $query[0]->nama_lokasi;
            $nama_prodi = $query[0]->nama_prodi;
            foreach ($query as $row):
                $bulan[] = $this->getBulan($row->bulan);
                $jumlah[] = $row->jumlah_bulanan;
            endforeach;

            $data=[
                'bulan' => $bulan, 
                'jumlah' => $jumlah,
                'lokasi' => $nama_lokasi,
                'prodi' => $nama_prodi
            ];

        } else {
            $lokasi = LokasiPelanggaran::where('id', $id)->first();
            $prodi = Prodi::where('id', $prodi)->first();
            
            $data=[
                'bulan' => null, 
                'jumlah' => null,
                'lokasi' => $lokasi->nama,
                'prodi' => $prodi->nama
            ];
        }
        return $data;
        
    }

    public function pelanggaranSanksiProdi($id = 1, $prodi = 1){
        $query = DB::select("SELECT DATE_FORMAT(tanggal, '%c') AS bulan, COUNT(*) AS jumlah_bulanan, prodi.nama as nama_prodi, sanksi.nama as nama_sanksi FROM pelanggaran_mahasiswa join sanksi on sanksi.id=pelanggaran_mahasiswa.sanksi_id join mahasiswa on mahasiswa.nim=pelanggaran_mahasiswa.nim join prodi on prodi.id=mahasiswa.prodi_id WHERE tanggal >= CURDATE() - INTERVAL 12 MONTH AND sanksi.id = '$id' AND prodi.id = '$prodi' GROUP BY YEAR(tanggal),MONTH(tanggal);");

        if(!empty($query)){
            $bulan = array();
            $jumlah = array();
            $nama_sanksi = $query[0]->nama_sanksi;
            $nama_prodi = $query[0]->nama_prodi;
            foreach ($query as $row):
                $bulan[] = $this->getBulan($row->bulan);
                $jumlah[] = $row->jumlah_bulanan;
            endforeach;

            $data=[
                'bulan' => $bulan, 
                'jumlah' => $jumlah,
                'sanksi' => $nama_sanksi,
                'prodi' => $nama_prodi
            ];

        } else {
            $sanksi = Sanksi::where('id', $id)->first();
            $prodi = Prodi::where('id', $prodi)->first();
            
            $data=[
                'bulan' => null, 
                'jumlah' => null,
                'sanksi' => $sanksi->nama,
                'prodi' => $prodi->nama
            ];
        }
        return $data;
        
    }
}


