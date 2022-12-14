<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KampusMahasiswa extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kampus_mahasiswa';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    public function scopeWhereKampus($query,$id_kampus)
    {
        if(gettype($id_kampus)=="array"){
            return $query->whereIn('id_kampus',$id_kampus);
        }
        else{
            return $query->where('id_kampus',$id_kampus);
        }
    }
    
    public function prodi()
    {
        return $this->hasOne(KampusProdi::class, 'id', 'id_prodi');
    }
    public function kelas()
    {
        return $this->hasOne(KampusKelas::class, 'id', 'id_kelas');
    }
    public function metode_belajar()
    {
        return $this->hasOne(KampusMetodeBelajar::class, 'id', 'id_metode_belajar');
    }
    public function lulusan()
    {
        return $this->hasOne(KampusLulusan::class, 'id', 'id_lulusan');
    }

    public function kampusMou()
    {
        return $this->hasOne(KampusMou::class, 'id', 'id_mou');
    }

    public function jadwal_ulang()
    {
        return $this->hasOne(KampusJadwalUlang::class, 'id_mahasiswa', 'id');
    }

    public function tagihan()
    {
        return $this->belongsTo(KampusTagihan::class, 'id_mahasiswa','id');
    }

    public function rencana()
    {
        return $this->belongsTo(KampusRencanaMahasiswa::class, 'id_mahasiswa','id');
    }
}