<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $table = "t_prodi";
    
    protected $fillable = [
        'nama_prodi',
        'id_ptn',
        'nama_prodi_ptn',
        'jenis',
        'active',
        'jenjang',
    ];

    public function ptn()
    {
        return $this->belongsTo(PTN::class, 'id_ptn', 'id_ptn');
    }
}
