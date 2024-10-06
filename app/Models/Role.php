<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        "nama",
        "group_id"
    ];

    protected $table = 'role';

    public function users()
    {
        return $this->hasMany(Users::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}