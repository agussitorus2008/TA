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
        'password',
        'jurusan',
        'email',
        'no_handphone',
        'password',
        'role'
    ];
}
