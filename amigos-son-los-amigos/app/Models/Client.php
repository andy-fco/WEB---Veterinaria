<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
        'direccion',
        'user_id', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'cliente_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'cliente_id');
    }


    public function bills()
    {
        return $this->hasMany(Bill::class, 'cliente_id');
    }
}
