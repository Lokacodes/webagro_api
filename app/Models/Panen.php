<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Panen extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'panen';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'tanggal_panen',
        'tanggal_tanam',
        'greenhouse_id',
        'created_at',
        'updated_at'
    ];

    public function sdm()
    {
        return $this->hasMany(SDM::class);
    }

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class);
    }

    public function pendapatan()
    {
        return $this->hasMany(Pendapatan::class);
    }

    public function modal()
    {
        return $this->hasMany(Modal::class);
    }

    public function greenhouse()
    {
        return $this->belongsTo(GreenHouse::class);
    }

    public function diagnosa()
    {
        return $this->hasMany(Diagnosa::class);
    }

    public function rekapAbsen()
    {
        return $this->hasMany(RekapAbsen::class, 'panen_id');
    }
}