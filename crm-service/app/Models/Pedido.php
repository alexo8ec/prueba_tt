<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'descripcion',
        'monto',
        'estado',
        'detalles'
    ];

    protected $casts = [
        'detalles' => 'array',
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
