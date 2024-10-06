<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kontrol extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "suhu_min",
        "suhu_max",
        "tds_min",
        "tds_max",
        "kelembaban_min",
        "kelembaban_max",
        "volume_min",
        "volume_max",
        "perangkat_id",
        'created_at',
        'updated_at'
    ];

    protected $table = "kontrol";

    public function perangkat()
    {
        return $this->belongsTo(Perangkat::class);
    }
}