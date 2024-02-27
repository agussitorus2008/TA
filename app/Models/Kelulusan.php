<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelulusan extends Model
{
    use HasFactory;

    protected $table = "kelulusan";

    protected $fillable = [
        'username',
        'kode_jalur',
        'id_prodi',
        'active',
        'jenjang'
    ];
}
