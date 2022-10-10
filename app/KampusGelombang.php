<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KampusGelombang extends Model
{
    protected $table = 'kampus_data_gelombang';
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
    public function scopeWithDefaultKampus($query)
    {
        return $query->orWhere('id_kampus',null);
    }
    
    public function item_bayar()
    {
        return $this->belongsTo(KampusItemBayar::class,'id_data_gelombang','id');
    }
    public function tahun_akademik()
    {
        return $this->hasOne(KampusTahunAkademik::class,'id','id_tahun_akademik');
    }
}