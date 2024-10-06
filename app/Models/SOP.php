<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SOP extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "id",
        "tanggal",
        "tugas",
        "catatan",
        "karyawan_id",
        "greenhouse_id",
        "tanaman_id",
        'created_at',
        'updated_at'
    ];

    protected $table = 'sop';

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function tanaman()
    {
        return $this->belongsTo(Tanaman::class);
    }

    public function greenhouse()
    {
        return $this->belongsTo(GreenHouse::class);
    }
}