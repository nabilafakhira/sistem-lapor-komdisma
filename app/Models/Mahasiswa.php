<?php

namespace App\Models;

use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = "mahasiswa";
    protected $guarded = ['nim'];
    public $timestamps = false;
    public $incrementing = false;
    var $column_order = array(null, 'nim', 'mahasiswa.nama', 'prodi.kode', 'kontak'); //set column field database for datatable orderable
    var $column_search = array('nim', 'mahasiswa.nama', 'prodi.kode'); //set column field database for datatable searchable
    var $order = array('user_id' => 'desc'); // default order 

    //Relation
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }
    public function pelanggaran_mahasiswa()
    {
        return $this->hasMany(PelanggaranMahasiswa::class, 'nim');
    }

    //function
    public function getMahasiswa($nim)
    {
        return Mahasiswa::where('nim', $nim)->first();
    }

    function get_datatables($postData)
    {
        $query = Mahasiswa::select('nim', 'mahasiswa.nama', 'user_id', 'kontak', 'prodi.kode')->join('prodi','prodi.id', '=', 'mahasiswa.prodi_id');
        // dd('inidimodel'.$postData);
        if ($postData != NULL) {
            $query->where('prodi.kode', $postData);
        }

        if (@$_POST['search']['value']) { // if datatable send POST for search
            $query->where(function ($query1) {
                $query1->where('nim', 'like', '%' . $_POST['search']['value'] . '%');
                $query1->orWhere('mahasiswa.nama', 'like', '%' . $_POST['search']['value'] . '%');
                $query1->orWhere('prodi.kode', 'like', '%' . $_POST['search']['value'] . '%');
            });
        }


        if (isset($_POST['order'])) { // here order processing
            $query->orderBy($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $query->orderBy(key($order), $order[key($order)]);
        }

        if (@$_POST['length'] != -1)
            $query->limit(@$_POST['length'], @$_POST['start']);
        return ['data' => $query->get(), 'count_filtered' => $query->count()];
    }
    function count_all()
    {
        $query = Mahasiswa::all();
        return $query->count();
    }
}
