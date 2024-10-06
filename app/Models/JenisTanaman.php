<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTanaman extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "nama",
    ];

    protected $table = "jenis_tanaman";

    public $timestamps = false;

    public function tanaman()
    {
        return $this->hasMany(Tanaman::class);
    }

    public function greenhosue()
    {
        return $this->hasMany(GreenHouse::class);
    }
}
