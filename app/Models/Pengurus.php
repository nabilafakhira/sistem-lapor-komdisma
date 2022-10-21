<?php

namespace App\Models;

use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Pengurus extends Model
{
    use HasFactory;

    protected $table = "pengurus";
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
    var $column_order = array(null, 'pengurus.id', 'nama'); //set column field database for datatable orderable
    var $order = array('user_id' => 'desc');

    //Relation
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pelanggaran_pelapor()
    {
        return $this->belongsTo(PelanggaranMahasiswa::class, 'pelapor');
    }

    public function pelanggaran_inspektur()
    {
        return $this->belongsTo(PelanggaranMahasiswa::class, 'inspektur');
    }

    //function
    public function getAllDosen()
    {
        $query = Pengurus::select('pengurus.id', 'pengurus.nama')
            ->join('users', 'users.id', '=', 'pengurus.user_id')
            ->whereIn('role', ['super-admin', 'admin', 'dosen'])
            ->orderBy('pengurus.nama', 'ASC')->get();

        return $query;
    }

    public function checkPengurus($id)
    {
        $query = Pengurus::find($id);

        return $query;
    }

    public function getPengurus($role)
    {
        $query = Pengurus::select('users.id as user_id', 'pengurus.id', 'nama', 'role', 'ttd')
            ->join('users', 'users.id', '=', 'pengurus.user_id')
            ->where('role', 'like', '%' . $role . '%')->orderBy('user_id', 'desc')->get();

        return $query;
    }

    function get_datatables(array $role)
    {
        $query = Pengurus::select('user_id', 'pengurus.id', 'nama', 'role')
        ->join('users', 'users.id', '=', 'pengurus.user_id')
        ->whereIn('role', $role);
        // ->orderBy('user_id', 'desc');
        
            if (@$_POST['search']['value']) { // if datatable send POST for search
                $query->where(function ($query1) {
                    $query1->where('pengurus.id', 'like', '%' . $_POST['search']['value'] . '%');
                    $query1->orWhere('nama', 'like', '%' . $_POST['search']['value'] . '%');
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
    function count_all(array $role)
    { 
        $query = User::whereIn('role', $role);
        return $query->count();
    }


}
