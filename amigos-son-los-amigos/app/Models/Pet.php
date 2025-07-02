<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'especie',
        'raza',
        'fecha_nacimiento',
        'cliente_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'cliente_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'id_mascota');
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'id_mascota');
    }

    public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class, 'id_mascota');
    }

    public function vaccines()
    {
        return $this->hasMany(Vaccine::class, 'id_mascota');
    }
}
