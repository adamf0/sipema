<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KampusPembayaran extends Model
{
    protected $table = 'kampus_pembayaran';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function scopeWhereKampus($query,$id_kampus)
    {
        return $query->where('id_kampus',$id_kampus);
    }
    
    public function chanel_pembayaran()
    {
        return $this->hasOne(MasterChannelPembayaran::class,'id','id_chanel_pembayaran');
    }
}