<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penyakit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "nama",
        "kode",
        'created_at',
        'updated_at'
    ];

    protected $table = "penyakit";

    public function pengetahuan()
    {
        return $this->hasMany(Pengetahuan::class);
    }

    public function diagnosa()
    {
        return $this->hasMany(Diagnosa::class);
    }

    public function post()
    {
        return $this->hasMany(Post::class);
    }

    public function riwayat_penyakit()
    {
        return $this->hasMany(RiwayatPenyakit::class);
    }
}