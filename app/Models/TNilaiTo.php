<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TNilaiTo extends Model
{
    use HasFactory;

    protected $table = "t_nilai_to";

    protected $fillable = [
        'username',
        'id_to',
        'nama_tryout',
        'tanggal',
        'pu',
        'ppu',
        'pbm',
        'pk',
        'lbi',
        'lbe',
        'pm',
        'active',
    ];

    protected $dates = [
        'tanggal',
    ];
}
