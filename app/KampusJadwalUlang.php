<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KampusJadwalUlang extends Model
{
    protected $table = 'kampus_jadwal_ulang_tagihan';
    public $timestamps = false;

    public function mahasiswa()
    {
        return $this->hasOne(KampusMahasiswa::class, 'id', 'id_mahasiswa');
    }
}
