<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengetahuan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "mb",
        "md",
        "penyakit_id",
        "gejala_id",
        'created_at',
        'updated_at',
    ];

    protected $table = "pengetahuan";

    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class);
    }

    public function gejala()
    {
        return $this->belongsTo(Gejala::class);
    }
}