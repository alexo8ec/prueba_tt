<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoDetalle extends Model
{
    protected $fillable = [
        'pedido_id',
        'producto',
        'cantidad',
        'precio',
        'subtotal'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}
