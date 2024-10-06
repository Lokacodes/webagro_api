<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gejala extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "nama",
        "kode",
        'created_at',
        'updated_at'
    ];

    protected $table = "gejala";

    public function pengetahuan()
    {
        return $this->hasMany(Pengetahuan::class);
    }

    public function riwayat()
    {
        return $this->hasMany(Riwayat::class);
    }
}