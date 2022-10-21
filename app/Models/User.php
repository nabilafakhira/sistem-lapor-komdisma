<?php

namespace App\Models;

use App\Models\Pengurus;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = "users";
    protected $guarded = ['id'];
    public $timestamps = false;
    var $column_order = array(null, 'username', 'email', 'role'); //set column field database for datatable orderable
    var $column_search = array('username', 'email', 'role'); //set column field database for datatable searchable
    var $order = array('id' => 'desc'); // default order 

    //Relation
    public function pengurus() 
    {
        return $this->hasOne(Pengurus::class, 'user_id');
    }
    public function mahasiswa() 
    {
        return $this->hasOne(Mahasiswa::class, 'user_id');
    }

    //function
    public function hasRole($role)
    {
        // check param $role dengan field role
        if ($role == $this->role) {
            return true;
        }
        return false;
    }

    public function checkUser($username){
        $check = User::where('username', $username)->first();
        if($check != null){
            return true;
        }
        return false;
    }

    public function getRole($username)
    {
        $role = User::where('username', $username)->first();
        if($role != null){
            return $role->role;
        }

        return false;
    }

    public function getProfile($user)
    {
        $role = $this->getRole($user->username);
        if($role != "mahasiswa"){
            return User::join('pengurus', 'users.id', '=', 'pengurus.user_id')->where('users.id',$user->id)->first();
        } else {
            return User::join('mahasiswa', 'users.id', '=', 'mahasiswa.user_id')->where('users.id',$user->id)->first();
        }
        return false;
    }

    public function regPengurus(array $data)
    {
        $user = new User;
        $user->username = $data['id'];
        $user->password = Hash::make($data['id']);
        $user->email = $data['id']."@gmail.com";
        $user->role = $data['role'];
        $user->save();

        $id= $user->id;
        $pengurus = new Pengurus;
        $pengurus->id = $data['id'];
        $pengurus->user_id = $id;
        $pengurus->nama = $data['nama'];
        $pengurus->save();

        return $pengurus;
    }

    public function regMahasiswa(array $data)
    {
        $user = new User;
        $user->username = $data['nim'];
        $user->password = Hash::make($data['nim']);
        $user->email = $data['nim']."@gmail.com";
        $user->role = $data['role'];
        $user->save();

        $id= $user->id;
        $mahasiswa = new Mahasiswa;
        $mahasiswa->nim = $data['nim'];
        $mahasiswa->user_id = $id;
        $mahasiswa->nama = $data['nama'];
        $mahasiswa->prodi_id = $data['prodi'];
        $mahasiswa->kontak = null;
        $mahasiswa->save();

        return $mahasiswa;
    }

    function get_datatables($postData)
    {
        $query = User::select('username', 'email', 'role');
        if ($postData != NULL) {
            $strRole = strtolower(str_replace(" ", "-", $postData));
            $query->where('role', $strRole);
        }

        if (@$_POST['search']['value']) { // if datatable send POST for search
            $query->where(function ($query1) {
                $query1->where('username', 'like', '%' . $_POST['search']['value'] . '%');
                $query1->orWhere('email', 'like', '%' . $_POST['search']['value'] . '%');
                $query1->orWhere('role', 'like', '%' . strtolower(str_replace(" ", "-", $_POST['search']['value'])) . '%');
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
    function count_all()
    {
        $query = User::all();
        return $query->count();
    }
}
