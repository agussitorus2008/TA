<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayaTampung extends Model
{
    use HasFactory;

    protected $table = "t_daya_tampung_prodi";

    protected $fillable = [
        'id_prodi',
        'tahun',
        'daya_tampung'
    ];
}
