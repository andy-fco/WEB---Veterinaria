<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function role()
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }


    public function client()
    {
        return $this->hasOne(Client::class, 'user_id');
    }


    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    public function administrator()
    {
        return $this->hasOne(Administrator::class, 'user_id');
    }



    public function isAdmin(): bool
    {
        return $this->rol_id === 3;
    }

    public function isEmployee(): bool
    {
        return $this->rol_id === 2;
    }

    public function isClient(): bool
    {
        return $this->rol_id === 1;
    }
}
