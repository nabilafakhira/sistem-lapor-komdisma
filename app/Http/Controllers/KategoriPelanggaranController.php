<?php

namespace App\Http\Controllers;

use App\Models\KategoriPelanggaran;
use Illuminate\Http\Request;

class KategoriPelanggaranController extends Controller
{
    public function getAjax()
    {
        $kategori = KategoriPelanggaran::all()->pluck('id');
        return response()->json($kategori);
    }
}
