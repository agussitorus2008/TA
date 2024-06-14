<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = "mv_rekapitulasi_nilai_to";
    protected $primaryKey = 'id';
    // public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'id_to',
        'id_nilai_to',
        'username',
        'first_name',
        'asal_sekolah',
        'NISN',
        'pilihan1_utbk',
        'pu_benar',
        'nilai_pu',
        'pbm_benar',
        'nilai_pbm',
        'ppu_benar',
        'nilai_ppu',
        'pk_benar',
        'nilai_pk',
        'pm_benar',
        'nilai_pm',
        'lbi_benar',
        'nilai_lbi',
        'lbe_benar',
        'nilai_lbe',
        'daya_tampung'
    ];
}
