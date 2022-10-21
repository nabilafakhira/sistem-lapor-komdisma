<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Prodi;

use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MahasiswaImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $mahasiswa = new Mahasiswa();
        $user = new User();
        if (($user->checkUser($row[0]) == true) or ($mahasiswa->getMahasiswa($row[0]) != null)) {
            return null;
        } else {
            $prodi_id = Prodi::where('kode', 'like', '%'.$row[2].'%')->first();
            $data = [
                'nim' => $row[0],
                'nama' => $row[1],
                'prodi' => $prodi_id->id,
                'role' => "mahasiswa"
            ];
            return $user->regMahasiswa($data);
        }
    }
}
