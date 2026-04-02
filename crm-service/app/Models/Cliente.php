<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono'
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
