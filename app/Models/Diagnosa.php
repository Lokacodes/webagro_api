<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diagnosa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "nama",
        "tanggal",
        "gambar",
        "greenhouse_id",
        "panen_id",
        "tanaman_id",
        'created_at',
        'updated_at'
    ];

    protected $table = "diagnosa";

    public function riwayat()
    {
        return $this->hasMany(Riwayat::class);
    }

    public function greenhouse()
    {
        return $this->belongsTo(GreenHouse::class);
    }

    public function panen()
    {
        return $this->belongsTo(Panen::class);
    }

    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class);
    }

    public function tanaman()
    {
        return $this->belongsTo(Tanaman::class);
    }

    public function riwayat_penyakit()
    {
        return $this->hasMany(RiwayatPenyakit::class);
    }
}