<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SDM extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "tanggal",
        "karyawan_id",
        "panen_id",
        "catatan",
        "nominal",
        'created_at',
        'updated_at'
    ];

    protected $table = 'sdm';

    public function panen()
    {
        return $this->belongsTo(Panen::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}