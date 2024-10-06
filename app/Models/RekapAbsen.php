<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RekapAbsen extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'rekap_absen';

    protected $fillable = [
        'panen_id',
        'karyawan_id',
        'jumlah',
        'created_at',
        'updated_at',
    ];

    public function panen()
    {
        return $this->belongsTo(Panen::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}