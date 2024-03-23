<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaOld extends Model
{
    use HasFactory;

    protected $table = "t_siswa";
    protected $primaryKey = 'username';

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

    public function pilihan1()
    {
        return $this->belongsTo(Prodi::class, 'pilihan1_utbk_aktual', 'id_prodi');
    }

    public function pilihan2()
    {
        return $this->belongsTo(Prodi::class, 'pilihan2_utbk_aktual', 'id_prodi');
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'asal_sekolah', 'id'); // Adjust relationship and key names accordingly
    }
}
