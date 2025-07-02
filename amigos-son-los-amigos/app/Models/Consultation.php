<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_hora',
        'motivo',
        'id_mascota',
        'id_empleado',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'id_mascota');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_empleado');
    }

    public function diagnosis()
    {
        return $this->hasOne(Diagnosis::class, 'id_consulta');
    }


}
