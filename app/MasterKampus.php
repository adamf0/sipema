<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterKampus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_kampus';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function user_kampus()
    {
        return $this->belongsTo(UserKampus::class, 'id_kampus', 'id');
    }

    public function metodePembayaran()
    {
        return $this->hasMany(KampusPembayaran::class, 'id_kampus', 'id');
    }
}