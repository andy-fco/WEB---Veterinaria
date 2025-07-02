<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_turno',
        'estado',
        'id_cliente',
        'id_mascota',
        'id_empleado',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_cliente');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'id_mascota');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_empleado');
    }
}
