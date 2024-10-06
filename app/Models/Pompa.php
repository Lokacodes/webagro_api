<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pompa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "id",
        "status",
        "auto",
        "keterangan",
        "perangkat_id",
        'created_at',
        'updated_at'
    ];

    protected $table = "pompa";

    public function perangkat()
    {
        return $this->belongsTo(Perangkat::class);
    }
}