<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TNilaiTo extends Model
{
    use HasFactory;

    protected $table = "t_nilai_to";
    // protected $primaryKey = "username";
    // protected $keyType = "string";
    // public $incrementing = false; 

    protected $fillable = [
        'username',
        'id_to',
        'tanggal',
        'pu',
        'ppu',
        'pbm',
        'pk',
        'lbi',
        'lbe',
        'pm',
        'active',
    ];

    protected $dates = [
        'tanggal',
    ];

    public function tryout()
    {
        return $this->belongsTo(TTo::class, 'id_to', 'id_to');
    }

    public function rekapitulasiNilai()
    {
        return $this->belongsTo(Nilai::class, 'username', 'username');
    }
}
