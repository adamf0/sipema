<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KampusTagihan extends Model
{
    protected $table = 'kampus_tagihan';
    public $timestamps = false;

    public function tagihan_detail()
    {
        return $this->hasMany(KampusTagihanDetail::class, 'id_transaksi', 'id');
    }
    public function scopeOld($query)
    {
        return $query->where('tanggal','<',date('Y-m-d'));
    }
    public function scopeNow($query)
    {
        return $query->where('tanggal','like',"%".date('Y-m-d')."%");
    }
    public function scopeWaiting($query)
    {
        return $query->where('status',0);
    }
    public function scopeSettlement($query)
    {
        return $query->where('status',1);
    }
    public function scopeReschedule($query)
    {
        return $query->where('status',-1);
    }
    public function mahasiswa()
    {
        return $this->hasOne(KampusMahasiswa::class, 'id','id_mahasiswa');
    }
}
