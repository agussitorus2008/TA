<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = "siswa";
    protected $primaryKey = 'id';

    protected $fillable = [
        'username',
        'telp1',
        'email',
        'first_name',
        'asal_sekolah',
        'kelompok_ujian',
        'pilihan1_utbk_aktual',
        'pilihan2_utbk_aktual',
        'active',
        'created_at',
        'updated_at'
    ];


    public function pilihan1()
    {
        return $this->belongsTo(Prodi::class, 'pilihan1_utbk_aktual', 'id_prodi');
    }

    public function pilihan2()
    {
        return $this->belongsTo(Prodi::class, 'pilihan2_utbk_aktual', 'id_prodi');
    }

    public function sekolahSMA()
    {
        return $this->belongsTo(Sekolah::class, 'asal_sekolah', 'id');
    }
}
