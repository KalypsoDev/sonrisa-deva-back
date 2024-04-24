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

    public function testDestroyMethodDeletesProduct()
    {
        // Crear un producto de prueba manualmente en la base de datos
        $product = Product::create([
            'name' => 'Producto a eliminar',
            'description' => 'Descripción del producto a eliminar',
            'price' => 49.99,
            'stock' => 5,
        ]);

        // Crear una instancia del controlador ProductController
        $controller = new ProductController();

        // Llamar al método `destroy` del controlador para eliminar el producto creado
        $response = $controller->destroy($product->id);

        // Verificar que la respuesta tenga un código HTTP 200 (éxito)
        $this->assertEquals(200, $response->getStatusCode());

        // Verificar que la respuesta sea un JSON válido
        $this->assertJson($response->getContent());

        // Decodificar el contenido JSON de la respuesta para analizarlo
        $responseData = json_decode($response->getContent(), true);

        // Verificar que la respuesta contenga el mensaje esperado
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Producto eliminado correctamente', $responseData['message']);

        // Verificar que el producto realmente se haya eliminado de la base de datos
        $deletedProduct = Product::find($product->id);
        $this->assertNull($deletedProduct, 'El producto debe haber sido eliminado de la base de datos');

        // Puedes agregar más aserciones aquí si necesitas verificar otros detalles relacionados con la eliminación

        // Si el producto no se ha eliminado correctamente, puedes limpiar la base de datos
        // Eliminar el producto manualmente si aún existe
        if ($deletedProduct) {
            $deletedProduct->delete();
        }
    }

    public function testUpdateMethodUpdatesProduct()
    {
        // Crear un producto de prueba manualmente en la base de datos
        $product = Product::create([
            'name' => 'Producto a actualizar',
            'description' => 'Descripción del producto a actualizar',
            'price' => 39.99,
            'stock' => 10,
        ]);

        // Crear una instancia del controlador ProductController
        $controller = new ProductController();

        // Definir los datos de la solicitud para actualizar el producto
        $requestData = [
            'name' => 'Producto actualizado',
            'description' => 'Nueva descripción del producto',
            'price' => 49.99,
            'stock' => 15,
        ];

        // Crear una instancia de Request con los datos de actualización y el ID del producto
        $request = new Request($requestData);

        // Llamar al método `update` del controlador para actualizar el producto creado
        $response = $controller->update($request, $product->id);

        // Verificar que la respuesta tenga un código HTTP 200 (éxito)
        $this->assertEquals(200, $response->getStatusCode());

        // Verificar que la respuesta sea un JSON válido
        $this->assertJson($response->getContent());

        // Decodificar el contenido JSON de la respuesta para analizarlo
        $responseData = json_decode($response->getContent(), true);

        // Verificar que la respuesta contenga el mensaje esperado
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Producto actualizado correctamente', $responseData['message']);

        // Verificar que los datos del producto se hayan actualizado correctamente en la base de datos
        $updatedProduct = Product::find($product->id);
        $this->assertEquals('Producto actualizado', $updatedProduct->name);
        $this->assertEquals('Nueva descripción del producto', $updatedProduct->description);
        $this->assertEquals(49.99, $updatedProduct->price);
        $this->assertEquals(15, $updatedProduct->stock);

        // Si el producto no se ha actualizado correctamente, puedes limpiar la base de datos
        // Eliminar el producto manualmente si aún existe
        if ($updatedProduct) {
            $updatedProduct->delete();
        }

        
    }
    
}




