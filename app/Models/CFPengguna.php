<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CFPengguna extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "kondisi",
        "nilai",
        'created_at',
        'updated_at',
    ];

    protected $table = "cf_pengguna";

    public function riwayat()
    {
        return $this->hasMany(Riwayat::class);
    }
}