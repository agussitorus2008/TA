<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = "t_siswa";

    protected $fillable = [
        'username',
        'telp1',
        'telp2',
        'password',
        'email',
        'role',
        'asal_sekolah',
        'kelompok_ujian',
        'pilihan1_utbk_aktual',
        'pilihan2_utbk_aktual',
    ];
}
