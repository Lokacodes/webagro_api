<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perangkat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "id",
        "nama",
        "keterangan",
        "greenhouse_id",
        'created_at',
        'updated_at'
    ];

    protected $table = "perangkat";

    public function greenhouse()
    {
        return $this->belongsTo(GreenHouse::class);
    }
    public function sensor()
    {
        return $this->hasMany(Sensor::class)->orderBy('created_at', 'DESC');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function pompa()
    {
        return $this->hasMany(Pompa::class);
    }


    public function kontrol()
    {
        return $this->hasMany(Kontrol::class);
    }
}
