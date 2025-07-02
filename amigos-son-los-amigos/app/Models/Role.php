<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;


    protected $fillable = ['nombre'];


    public function users()
    {
        // 'id_rol' es la clave foránea en la tabla 'users' que apunta al 'id' de 'roles'
        return $this->hasMany(User::class, 'rol_id');
    }
}