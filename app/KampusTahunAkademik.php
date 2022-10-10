<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KampusTahunAkademik extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kampus_tahun_akademik';

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
    
    public function gelombang()
    {
        return $this->belongsTo(KampusGelombang::class,'id_tahun_akademik','id');
    }
}
