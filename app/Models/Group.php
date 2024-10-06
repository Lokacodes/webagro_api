<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        "nama"
    ];

    protected $table = 'group';

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}