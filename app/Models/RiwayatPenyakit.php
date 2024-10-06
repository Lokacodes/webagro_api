<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatPenyakit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "riwayat_penyakit";

    protected $fillable = [
        "nilai",
        "bobot",
        "penyakit_id",
        "diagnosa_id",
        "created_at",
        "updated_at"
    ];

    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class);
    }

    public function diagnosa()
    {
        return $this->belongsTo(Diagnosa::class);
    }
}