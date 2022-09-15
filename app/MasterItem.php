<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_item';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function biaya_potong()
    {
        return $this->belongsTo(BiayaPotongan::class,'id_item','id');
    }
    public function item_bayar()
    {
        return $this->belongsTo(KampusItemBayar::class,'id_item','id');
    }
}