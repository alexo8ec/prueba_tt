<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Cliente;

class ClienteTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(); // Desactiva todos los middleware para los tests
    }

    /** @test */
    public function puede_crear_cliente()
    {
        $payload = [
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'telefono' => '123456789',
        ];

        $response = $this->postJson('/api/v1/clientes', $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nombre' => 'Juan',
                     'apellido' => 'Pérez',
                     'email' => 'juan.perez@example.com',
                     'telefono' => '123456789',
                 ]);

        $this->assertDatabaseHas('clientes', [
            'email' => 'juan.perez@example.com',
        ]);
    }
    /** @test */
    public function puede_listar_clientes()
    {
        Cliente::factory()->count(2)->create();
        $response = $this->getJson('/api/v1/clientes');
        $response->assertStatus(200);
        $this->assertCount(2, $response->json());
    }

    /** @test */
    public function puede_mostrar_cliente()
    {
        $cliente = Cliente::factory()->create();
        $response = $this->getJson('/api/v1/clientes/' . $cliente->id);
        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $cliente->id,
                     'nombre' => $cliente->nombre,
                     'apellido' => $cliente->apellido,
                     'email' => $cliente->email,
                 ]);
    }

    /** @test */
    public function puede_actualizar_cliente()
    {
        $cliente = Cliente::factory()->create();
        $payload = [
            'nombre' => 'NombreNuevo',
            'apellido' => 'ApellidoNuevo',
            'email' => 'nuevo@email.com',
            'telefono' => '555555',
        ];
        $response = $this->putJson('/api/v1/clientes/' . $cliente->id, $payload);
        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nombre' => 'NombreNuevo',
                     'apellido' => 'ApellidoNuevo',
                     'email' => 'nuevo@email.com',
                     'telefono' => '555555',
                 ]);
        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id,
            'email' => 'nuevo@email.com',
        ]);
    }

    /** @test */
    public function puede_eliminar_cliente()
    {
        $cliente = Cliente::factory()->create();
        $response = $this->deleteJson('/api/v1/clientes/' . $cliente->id);
        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'status' => 'success',
                 ]);
        $this->assertDatabaseMissing('clientes', [
            'id' => $cliente->id,
        ]);
    }
}
