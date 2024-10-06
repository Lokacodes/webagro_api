<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Riwayat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "cf_id",
        "diagnosa_id",
        "jenis",
        "gejala_id",
        'created_at',
        'updated_at'
    ];

    protected $table = "riwayat";

    public function cf()
    {
        return $this->belongsTo(CFPengguna::class);
    }

    public function diagnosa()
    {
        return $this->belongsTo(Diagnosa::class);
    }

    public function gejala()
    {
        return $this->belongsTo(Gejala::class);
    }
}