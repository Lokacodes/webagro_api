<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "id",
        "nik",
        "nama",
        "alamat",
        "jkel",
        "jabatan_id",
        "user_id",
        "greenhouse_id",
        'created_at',
        'updated_at'
    ];

    protected $table = 'karyawan';

    public function user()
    {
        return $this->belongsTo(Users::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function sop()
    {
        return $this->hasMany(SOP::class);
    }
    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function sdm()
    {
        return $this->hasMany(SDM::class);
    }

    public function greenhouse()
    {
        return $this->belongsTo(GreenHouse::class);
    }

    public function rekapAbsen()
    {
        return $this->hasMany(RekapAbsen::class);
    }
}