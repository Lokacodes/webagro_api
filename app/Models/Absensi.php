<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Absensi extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "id",
        "tanggal",
        "status",
        "catatan",
        "karyawan_id",
        "latitude",
        "longitude",
        "created_at",
        "updated_at"
    ];

    protected $table = 'absensi';

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}