<?php

namespace Tests\Unit;

use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Http\Request;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index method returns all products in JSON format.
     *
     * @return void
     */

     public function testIndexReturnsAllProducts()
     {
        $controller = new ProductController();

        $response = $controller->index();
    
        $this->assertEquals(200, $response->status()); 
    
        $this->assertJson($response->content());

        $responseArray = json_decode($response->content(), true);
        $this->assertIsArray($responseArray);
    
        foreach ($responseArray as $product) {
            $this->assertArrayHasKey('id', $product);
            $this->assertArrayHasKey('name', $product);
            $this->assertArrayHasKey('description', $product);
            $this->assertArrayHasKey('price', $product);
            $this->assertArrayHasKey('stock', $product);
        }
    }

    public function testShowMethodReturnsProduct()
    {
        // Crear un producto de prueba manualmente en la base de datos
        $product = Product::create([
            'name' => 'Producto de prueba',
            'description' => 'Descripción del producto de prueba',
            'price' => 29.99,
            'stock' => 10,
        ]);

        // Crear una instancia del controlador ProductController
        $controller = new ProductController();

        // Simular una solicitud HTTP GET con el ID del producto
        $request = Request::create('/products/' . $product->id, 'GET'); // Utiliza Request::create

        // Ejecutar el método `show` del controlador con la solicitud simulada
        $response = $controller->show($product->id);

        // Verificar que la respuesta tenga un código HTTP 200 (éxito)
        $this->assertEquals(200, $response->getStatusCode());

        // Verificar que la respuesta sea un JSON válido
        $this->assertJson($response->getContent());

        // Decodificar el contenido JSON de la respuesta para analizarlo
        $responseData = json_decode($response->getContent(), true);

        // Verificar que la respuesta contenga la información del producto
        $this->assertArrayHasKey('data', $responseData);
        $this->assertEquals($product->id, $responseData['data']['id']);
        $this->assertEquals($product->name, $responseData['data']['name']);
        $this->assertEquals($product->description, $responseData['data']['description']);
        $this->assertEquals($product->price, $responseData['data']['price']);
        $this->assertEquals($product->stock, $responseData['data']['stock']);
        
        // Puedes agregar más aserciones aquí si necesitas verificar otros campos del producto

        // Eliminar el producto de prueba después de la prueba
        $product->delete();
    }
    
}




