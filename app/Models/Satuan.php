<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "nama",
    ];

    protected $table = "satuan";

    public function pupuk()
    {
        return $this->hasMany(Pupuk::class);
    }
}