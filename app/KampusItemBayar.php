<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KampusItemBayar extends Model
{
    protected $table = 'kampus_item_bayar';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function scopeWhereKampus($query,$id_kampus)
    {
        if(gettype($id_kampus)=="array"){
            return $query->whereIn('id_kampus',$id_kampus);
        }
        else{
            return $query->where('id_kampus',$id_kampus);
        }
    }
    
    public function gelombang()
    {
        return $this->hasOne(KampusGelombang::class,'id','id_data_gelombang');
    }
    public function item()
    {
        return $this->hasOne(MasterItem::class,'id','id_item');
    }
    public function prodi()
    {
        return $this->hasOne(KampusProdi::class,'id','id_prodi');
    }
    public function kelompok()
    {
        return $this->hasOne(MasterKelompok::class,'id','id_kelompok');
    }
    public function rencana()
    {
        return $this->belongsTo(KampusRencanaMahasiswa::class, 'id_item_bayar', 'id');
    }
}