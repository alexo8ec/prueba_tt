<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'cliente_id',
        'total',
        'estado'
    ];

    public function detalles()
    {
        return $this->hasMany(PedidoDetalle::class);
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
