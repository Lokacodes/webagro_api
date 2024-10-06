<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengeluaran extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "tanggal",
        "kategori",
        "panen_id",
        "produk",
        "satuan_id",
        "catatan",
        "jumlah",
        "nominal",
        "greenhouse_id",
        'created_at',
        'updated_at'
    ];

    protected $table = 'pengeluaran';

    public function greenhouse()
    {
        return $this->belongsTo(GreenHouse::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function panen()
    {
        return $this->belongsTo(Panen::class);
    }
}