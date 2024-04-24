<?php

namespace Tests\Unit;

use App\Http\Controllers\EventController;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase; // Usar esta trait para realizar pruebas con base de datos

    public function testIndexMethod()
    {
        // Crear un mock de la clase Event para simular una colección de eventos vacía
        $mockEvent = Mockery::mock(Event::class);
        $mockEvent->shouldReceive('all')->andReturn(collect()); // Simular que no hay eventos en la base de datos

        // Inyectar el mock en el controlador
        $controller = new EventController($mockEvent);

        // Realizar una solicitud HTTP simulada al método index del controlador
        $response = $controller->index();

        // Verificar que la respuesta tiene un código HTTP 200 (éxito)
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        // Verificar que la respuesta contiene una estructura JSON vacía
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals([], $responseData); // Esperamos un arreglo vacío de eventos

        // Finalizar el mock
        Mockery::close();
    }
}


