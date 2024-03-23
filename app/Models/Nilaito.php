<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilaito extends Model
{
    use HasFactory;

    protected $table = "nilai_to";

    protected $fillable = [
        'username',
        'nama_tryout',
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
