<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sanksi extends Model
{
    use HasFactory;

    protected $table = "sanksi";
    protected $guarded = ['id'];
    public $timestamps = false;

    //function
    public function getSkorsing($id){
        $query = PelanggaranMahasiswa::select('skorsing')
        ->leftJoin('sanksi', 'sanksi.id', '=', 'pelanggaran_mahasiswa.sanksi_id')
        ->where('pelanggaran_mahasiswa.id', $id)->first();

        return $query;
    }
}
