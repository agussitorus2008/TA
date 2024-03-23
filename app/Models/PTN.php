<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PTN extends Model
{
    use HasFactory;

    protected $table = "t_ptn";
    
    protected $fillable = [
        'id_ptn',
        'nama_ptn',
        'nama_singkat',
    ];

}
