<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KampusLulusan extends Model
{
    protected $table = 'kampus_lulusan';
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
        return $this->belongsTo(KampusItemBayar::class, 'id_lulusan', 'id');
    }
    public function jenjang()
    {
        return $this->hasOne(MasterJenjang::class, 'id', 'prasyarat_jenjang');
    }
    public function mahasiswa()
    {
        return $this->belongsTo(KampusMahasiswa::class, 'id_lulusan', 'id');
    }
}
