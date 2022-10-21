<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->username = "202103198710102001";
        $user->password = Hash::make('202103198710102001');
        $user->email = "202103198710102001@gmail.com";
        $user->role = "super-admin";
        $user->save();

        $user = new User;
        $user->username = "J3C118135";
        $user->password = Hash::make('J3C118135');
        $user->email = "J3C118135@gmail.com";
        $user->role = "mahasiswa";
        $user->save();
    }
}
