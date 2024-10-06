<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tanaman extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "kode",
        "pertumbuhan",
        "jenis_tanaman_id",
        "greenhouse_id",
        'created_at',
        'updated_at',
    ];

    protected $table = "tanaman";

    public function jenis_tanaman()
    {
        return $this->belongsTo(JenisTanaman::class);
    }

    public function kontrol_lingkungan()
    {
        return $this->hasMany(KontrolLingkungan::class);
    }

    public function diagnosa()
    {
        return $this->hasMany(Diagnosa::class);
    }

    public function greenhouse()
    {
        return $this->belongsTo(GreenHouse::class);
    }

    public function sop()
    {
        return $this->belongsTo(SOP::class);
    }
}