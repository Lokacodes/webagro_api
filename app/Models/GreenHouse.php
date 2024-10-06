<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GreenHouse extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'greenhouse';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'nama',
        'pemilik',
        'alamat',
        'ukuran',
        'gambar',
        'pengelola',
        'latitude',
        'longitude',
        'telegram_id',
        'jenis_tanaman_id',
        'created_at',
        'updated_at',
    ];

    public function perangkat()
    {
        return $this->hasMany(Perangkat::class);
    }

    public function panen()
    {
        return $this->hasMany(Panen::class);
    }

    public function pendapatan()
    {
        return $this->hasMany(Pendapatan::class);
    }

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class);
    }

    public function diagnosa()
    {
        return $this->hasMany(Diagnosa::class);
    }

    public function pupuk()
    {
        return $this->hasMany(Pupuk::class);
    }

    public function tanaman()
    {
        return $this->hasMany(Tanaman::class);
    }

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }

    public function satuan()
    {
        return $this->hasMany(Satuan::class);
    }

    public function jenis_tanaman()
    {
        return $this->belongsTo(JenisTanaman::class);
    }
}