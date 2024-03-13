<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tryout extends Model
{
    use HasFactory;

    protected $table = "t_to";

    protected $fillable = [
        'id_to',
        'username',
        'tanggal',
        'tanggal_next_to',
        'nama_to_ah',
        'tangal_ah',
        't_pu',
        't_ppu',
        't_pbm',
        't_pk',
        't_matsaintek',
        't_fis',
        't_kim',
        't_bio',
        't_matsoshum',
        't_sej',
        't_eko',
        't_sos',
        't_geo',
        't_ing',
        't_lbi',
        't_lbe',
        't_pm',
        'total_saintek',
        'tota_soshum',
        'active',
    ];
}
