<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TTo extends Model
{
    use HasFactory;

    protected $table = "t_to";
    protected $primaryKey = "id_to";
    
    protected $fillable = [
        'nama_to',
        'tanggal',
        't_pu',
        't_ppu',
        't_pbm',
        't_pk',
        't_lbi',
        't_lbe',
        't_pm'
    ];

    public function nilaiTo()
    {
        return $this->hasMany(TNilaito::class, 'id_to', 'id_to');
    }
}
