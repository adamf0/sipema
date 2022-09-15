<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KampusRencanaMahasiswa extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kampus_rencana_mahasiswa';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}