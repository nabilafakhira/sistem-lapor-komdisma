<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $table = "prodi";
    protected $guarded = ['id'];
    public $timestamps = false;

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'prodi_id');
    }

}
