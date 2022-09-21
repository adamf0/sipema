<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KampusTagihanDetail extends Model
{
    protected $table = 'kampus_tagihan_detail';
    public $timestamps = false;

    public function tagihan()
    {
        return $this->belongsTo(KampusTagihan::class, 'id', 'id_transaksi');//belongsTo
    }
    public function rencana()
    {
        return $this->hasOne(KampusRencanaMahasiswa::class, 'id', 'id_tagihan_mahasiswa');
    }
}
