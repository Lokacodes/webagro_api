<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modal extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        "tanggal",
        "catatan",
        "nominal",
        "panen_id",
        'created_at',
        'updated_at'
    ];

    protected $table = 'modal';

    public function panen()
    {
        return $this->belongsTo(Panen::class);
    }
}