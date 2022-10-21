<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiPelanggaran extends Model
{
    use HasFactory;

    protected $table = "lokasi_pelanggaran";
    protected $guarded = ['id'];
    public $timestamps = false;

    //function
    public function pelanggaran_mahasiswa()
    {
        return $this->belongsTo(PelanggaranMahasiswa::class, 'lokasi_id');
    }
}
