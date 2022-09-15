<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KampusProdi extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kampus_prodi';

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

    public function kampus()
    {
        return $this->hasOne(MasterKampus::class, 'id', 'id_kampus');
    }

    public function kampus_item_bayar()
    {
        return $this->belongsTo(KampusItemBayar::class, 'id_kelompok', 'id');
    }
}