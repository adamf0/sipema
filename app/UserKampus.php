<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserKampus extends Model
{
    protected $table = 'users_kampus';
    public $timestamps = false;

    public function user()
    {
        return $this->hasMany(User::class,'id_user','id');
    }
    public function kampus()
    {
        return $this->hasOne(MasterKampus::class,'id','id_kampus');
    }
}
