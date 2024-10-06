<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "nama",
        "detail",
        "saran",
        "gambar",
        "penyakit_id",
        'created_at',
        'updated_at'
    ];

    protected $table = "post";

    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class);
    }
}