<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Users extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'id',
        'username',
        'email',
        'password',
        'role_id',
        'nama',
        'no_hp',
        'foto',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'password',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        $role = Role::join('group', 'role.group_id', '=', 'group.id')->where('role.id', $this->role_id)->select(['group.id as group_id'])->first();
        return [
            'payload' => [
                'user_id' => $this->id,
                'role_id' => $this->role_id,
                'group_id' => $role->group_id
            ]
        ];
    }
}
