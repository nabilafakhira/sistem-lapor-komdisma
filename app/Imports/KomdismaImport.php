<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Pengurus;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class KomdismaImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }
    
    public function model(array $row)
    {
        $pengurus = new Pengurus();
        $user = new User();
        if (($user->checkUser($row[0]) == true) OR ($pengurus->checkPengurus($row[0]) != null)) {
            return null;
        } else {
            $data = [
                'id' => $row[0],
                'nama' => $row[1],
                'role' => "admin"
            ];
            return $user->regPengurus($data);
        }
    }
    
}
