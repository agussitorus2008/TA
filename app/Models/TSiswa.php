<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TSiswa extends Model
{
    use HasFactory;

    protected $table = "t_siswa";
    protected $primaryKey = 'username';
    protected $keyType = 'string';

    protected $fillable = [
        'username',
        'first_name',
        'telp1',
        'telp2',
        'password',
        'email',
        'role',
        'active',
        'asal_sekolah',
        'kelompok_ujian',
        'pilihan1_utbk',
        'pilihan2_utbk',
    ];

    public function pilihan1()
    {
        return $this->belongsTo(Prodi::class, 'pilihan1_utbk', 'id_prodi');
    }

    public function pilihan2()
    {
        return $this->belongsTo(Prodi::class, 'pilihan2_utbk', 'id_prodi');
    }

    public function sekolah_siswa()
    {
        return $this->belongsTo(Sekolah::class, 'asal_sekolah', 'id');
    }

}
