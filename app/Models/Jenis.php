<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "nama"
    ];

    protected $table = "jenis";

    public function pupuk()
    {
        return $this->hasMany(Pupuk::class);
    }
}