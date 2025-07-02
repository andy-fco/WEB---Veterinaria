<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_factura',
        'monto_total',
        'estado',
        'id_cliente',

    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_cliente');
    }


}
