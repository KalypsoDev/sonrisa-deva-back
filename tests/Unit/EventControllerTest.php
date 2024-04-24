<?php

namespace Tests\Unit;

use App\Http\Controllers\EventController;
use App\Models\Event;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class EventControllerTest extends TestCase
{ // Usar esta trait para realizar pruebas con base de datos

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

    public function testShowEventFound()
    {
        // Crear un evento simulado con un ID válido
        $validId = '001';
        $event = new Event();
        $event->id = $validId;
        $event->title = 'Sample Event';
        $event->location = 'Sample Event';
        $event->save();

        // Crear una instancia del controlador
        $controller = new EventController();

        // Llamar al método show con el ID válido
        $response = $controller->show($validId);

        // Verificar que la respuesta tiene un código HTTP 200 (éxito)
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        // Verificar que la respuesta contiene los datos del evento en formato JSON
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertEquals($validId, $responseData['data']['id']);
        $this->assertEquals('Sample Event', $responseData['data']['title']);
        $this->assertEquals('Sample Event', $responseData['data']['location']);

        // Eliminar el evento creado para limpiar la base de datos
        $event->delete();
    }

    public function testShowEventNotFound()
    {
        // ID inválido que no existe en la base de datos
        $invalidId = 'invalid_id';

        // Crear una instancia del controlador
        $controller = new EventController();

        // Intentar llamar al método show con un ID inválido
        $response = $controller->show($invalidId);

        // Verificar que la respuesta tiene un código HTTP 404 (no encontrado)
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());

        // Verificar que la respuesta contiene el mensaje de error esperado en formato JSON
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('No se ha encontrado el evento', $responseData['error']);
    }


    }
    




