<?php

namespace App\Models;

use App\Models\JenisPelanggaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriPelanggaran extends Model
{
    use HasFactory;

    protected $table = "kategori_pelanggaran";
    protected $guarded = ['id'];
    public $timestamps = false;

    //Relation
    public function jenisp() {
        return $this->hasMany(JenisPelanggaran::class, 'kategori_pelanggaran_id');
    }

    //Function
}
