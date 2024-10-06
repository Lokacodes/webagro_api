<?php

namespace App\Models;

use App\Http\Controllers\PupukController;
use App\Http\Controllers\TanamanController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KontrolLingkungan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "id",
        "catatan",
        "tanggal",
        "pupuk_id",
        "tanaman_id",
        'created_at',
        'updated_at'
    ];

    protected $table = "kontrol_lingkungan";

    public function pupuk()
    {
        return $this->belongsTo(Pupuk::class);
    }

    public function tanaman()
    {
        return $this->belongsTo(Tanaman::class);
    }
}