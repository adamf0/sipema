<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterJenjang extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_jenjang';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function prodi()
    {
        return $this->belongsTo(KampusProdi::class, 'id_jenjang', 'id');
    }
    public function lulusan()
    {
        return $this->belongsTo(KampusLulusan::class, 'prasyarat_jenjang', 'id');
    }
}
