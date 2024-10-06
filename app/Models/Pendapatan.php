<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pendapatan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "tanggal",
        "nama_pembeli",
        "alamat_pembeli",
        "kategori",
        "catatan",
        "produk",
        "jumlah",
        "nominal",
        "panen_id",
        "satuan_id",
        "greenhouse_id",
        "user_id",
        'created_at',
        'updated_at'
    ];

    protected $table = 'pendapatan';

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