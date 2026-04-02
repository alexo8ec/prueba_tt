<?php

namespace Database\Factories;

use App\Models\Pedido;
use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoFactory extends Factory
{
    protected $model = Pedido::class;

    public function definition()
    {
        return [
            'cliente_id' => null, // Se asigna en el test
            'descripcion' => $this->faker->sentence(),
            'monto' => $this->faker->randomFloat(2, 10, 1000),
            'estado' => 'pendiente',
            'detalles' => [
                ['producto' => 'Producto 1', 'cantidad' => 2, 'precio' => 10.5],
                ['producto' => 'Producto 2', 'cantidad' => 1, 'precio' => 20.0],
            ],
        ];
    }
}
