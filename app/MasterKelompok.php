<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterKelompok extends Model
{
    protected $table = 'master_kelompok';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function item_bayar()
    {
        return $this->belongsTo(KampusItemBayar::class,'id_kelompok','id');
    }
}
