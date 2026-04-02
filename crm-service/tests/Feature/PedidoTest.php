<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Pedido;
use App\Models\Cliente;

class PedidoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

    /** @test */
    public function puede_crear_pedido()
    {
        $cliente = Cliente::factory()->create();
        $payload = [
            'cliente_id' => $cliente->id,
            'descripcion' => 'Pedido de prueba',
            'monto' => 123.45,
            'estado' => 'pendiente',
            'detalles' => [
                ['producto' => 'Producto 1', 'cantidad' => 2, 'precio' => 10.5],
                ['producto' => 'Producto 2', 'cantidad' => 1, 'precio' => 20.0],
            ],
        ];
        $response = $this->postJson('/api/v1/pedidos', $payload);
        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'cliente_id' => $cliente->id,
                     'descripcion' => 'Pedido de prueba',
                     'monto' => 123.45,
                 ]);
        $this->assertDatabaseHas('pedidos', [
            'cliente_id' => $cliente->id,
            'descripcion' => 'Pedido de prueba',
        ]);
    }

    /** @test */
    public function puede_listar_pedidos()
    {
        $cliente = \App\Models\Cliente::factory()->create();
        Pedido::factory()->count(2)->create(['cliente_id' => $cliente->id]);
        $response = $this->getJson('/api/v1/pedidos');
        $response->assertStatus(200);
        $this->assertCount(2, $response->json());
    }

    /** @test */
    public function puede_mostrar_pedido()
    {
        $cliente = \App\Models\Cliente::factory()->create();
        $pedido = Pedido::factory()->create(['cliente_id' => $cliente->id]);
        $response = $this->getJson('/api/v1/pedidos/' . $pedido->id);
        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $pedido->id,
                     'descripcion' => $pedido->descripcion,
                     'monto' => $pedido->monto,
                 ]);
    }

    /** @test */
    public function puede_actualizar_pedido()
    {
        $cliente = \App\Models\Cliente::factory()->create();
        $pedido = Pedido::factory()->create(['cliente_id' => $cliente->id]);
        $payload = [
            'descripcion' => 'Pedido actualizado',
            'monto' => 999.99,
            'estado' => 'completado',
            'detalles' => [
                ['producto' => 'Producto actualizado', 'cantidad' => 1, 'precio' => 99.99],
            ],
        ];
        $response = $this->putJson('/api/v1/pedidos/' . $pedido->id, $payload);
        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'descripcion' => 'Pedido actualizado',
                     'monto' => 999.99,
                 ]);
        $this->assertDatabaseHas('pedidos', [
            'id' => $pedido->id,
            'descripcion' => 'Pedido actualizado',
        ]);
    }

    /** @test */
    public function puede_eliminar_pedido()
    {
        $cliente = \App\Models\Cliente::factory()->create();
        $pedido = Pedido::factory()->create(['cliente_id' => $cliente->id]);
        $response = $this->deleteJson('/api/v1/pedidos/' . $pedido->id);
        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'status' => 'success',
                 ]);
        $this->assertDatabaseMissing('pedidos', [
            'id' => $pedido->id,
        ]);
    }
}
