<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrator extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
