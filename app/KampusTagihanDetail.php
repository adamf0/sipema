<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KampusTagihanDetail extends Model
{
    protected $table = 'kampus_tagihan_detail';
    public $timestamps = false;

    public function kampus()
    {
        return $this->belongsTo(KampusTagihan::class, 'id', 'id_transaksi');
    }
}
