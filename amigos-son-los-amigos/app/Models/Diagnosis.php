<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;


    protected $table = 'diagnoses';

    protected $fillable = [
        'id_mascota',
        'id_empleado',
        'descripcion',
        'tratamiento',

    ];




    public function pet()
    {
        return $this->belongsTo(Pet::class, 'id_mascota');
    }


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_empleado');
    }
}