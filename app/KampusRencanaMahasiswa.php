<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KampusRencanaMahasiswa extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kampus_rencana_mahasiswa';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false; 

    public function mahasiswa()
    {
        return $this->hasOne(KampusMahasiswa::class, 'id', 'id_mahasiswa');
    }
    public function tagihan_detail()
    {
        return $this->belongsTo(KampusTagihanDetail::class, 'id_tagihan_mahasiswa', 'id'); //belongsTo
    }
    public function item_bayar()
    {
        return $this->hasOne(KampusItemBayar::class, 'id', 'id_item_bayar');
    }
}