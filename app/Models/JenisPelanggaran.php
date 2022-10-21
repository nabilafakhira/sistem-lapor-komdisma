<?php

namespace App\Models;

use App\Models\KategoriPelanggaran;
use App\Models\PelanggaranMahasiswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisPelanggaran extends Model
{
    use HasFactory;

    protected $table = "jenis_pelanggaran";
    protected $guarded = ['id'];
    public $timestamps = false;

    public function kategorip()
    {
        return $this->belongsTo(KategoriPelanggaran::class, 'kategori_pelanggaran_id');
    }

    public function pelanggaran()
    {
        return $this->belongsTo(PelanggaranMahasiswa::class, 'jenis_pelanggaran_id');
    }


}
