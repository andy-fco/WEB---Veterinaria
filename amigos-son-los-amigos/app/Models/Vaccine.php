<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'fecha_aplicacion',
        'id_mascota',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'id_mascota');
    }
}
