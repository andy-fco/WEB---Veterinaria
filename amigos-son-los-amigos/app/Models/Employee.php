<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory; // Mantener HasFactory

    // Laravel infiere 'employees' si el modelo es 'Employee'. Si no, podrías poner protected $table = 'employees';

    protected $fillable = [
        'nombre',
        'apellido',
        'especialidad',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'id_empleado');
    }

}
