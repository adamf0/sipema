<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BiayaPotongan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'biaya_potongan';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function master_item()
    {
        return $this->hasOne(MasterItem::class,'id','id_item');
    }
}