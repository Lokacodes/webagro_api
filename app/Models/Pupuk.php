<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pupuk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "tanggal",
        "kandungan",
        "harga",
        "jumlah",
        "jenis_id",
        "satuan_id",
        "greenhouse_id",
        'created_at',
        'updated_at'
    ];

    protected $table = "pupuk";

    public function jenis()
    {
        return $this->belongsTo(Jenis::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function kontrol_lingkungan()
    {
        return $this->hasMany(KontrolLingkungan::class);
    }

    public function greenhouse()
    {
        return $this->belongsTo(GreenHouse::class);
    }
}