<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notifikasi extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "id",
        "status",
        "keterangan",
        "perangkat_id",
        "color",
        "created_at",
        "updated_at"
    ];

    protected $table = "notifikasi";

    public function perangkat()
    {
        return $this->belongsTo(Perangkat::class);
    }
}
