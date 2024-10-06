<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sensor extends Model
{
    use HasFactory;

    protected $fillable = [
        "sensor_suhu",
        "sensor_kelembaban",
        "sensor_ldr",
        "sensor_tds",
        "sensor_waterflow",
        "sensor_volume",
        "perangkat_id",
    ];

    protected $table = "sensor";

    public function perangkat()
    {
        return $this->belongsTo(Perangkat::class);
    }
}