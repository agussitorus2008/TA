<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewNilaiFinalTerbaru extends Model
{
    use HasFactory;

    protected $table  = "view_rekapitulasi_nilai_to_terbaru";

    protected $fillable = [
        'username',
        'pilihan1_utbk_aktual',
        'pilihan2_utbk_aktual',
        'average_to'
    ];
}
