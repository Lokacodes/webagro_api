<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "nama",
    ];

    protected $table = 'jabatan';

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }
}