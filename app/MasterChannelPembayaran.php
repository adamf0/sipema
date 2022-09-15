<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterChannelPembayaran extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_chanel_pembayaran';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function kampus_pembayaran()
    {
        return $this->belongsTo(KampusPembayaran::class,'id_chanel_pembayaran','id');
    }
}