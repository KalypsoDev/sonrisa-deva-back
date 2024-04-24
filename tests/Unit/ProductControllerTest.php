<?php

namespace Tests\Unit;

use App\Http\Controllers\ProductController;
use App\Models\Product;
use Tests\TestCase;
use Illuminate\Http\Request;

class ProductControllerTest extends TestCase
{

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
        $product = Product::create([
            'name' => 'Producto de prueba',
            'description' => 'Descripción del producto de prueba',
            'price' => 29.99,
            'stock' => 10,
        ]);


        $controller = new ProductController();

 
        $request = Request::create('/products/' . $product->id, 'GET'); 
        $response = $controller->show($product->id);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('data', $responseData);
        $this->assertEquals($product->id, $responseData['data']['id']);
        $this->assertEquals($product->name, $responseData['data']['name']);
        $this->assertEquals($product->description, $responseData['data']['description']);
        $this->assertEquals($product->price, $responseData['data']['price']);
        $this->assertEquals($product->stock, $responseData['data']['stock']);
        

        $product->delete();
    }

    public function testDestroyMethodDeletesProduct()
    {
        $product = Product::create([
            'name' => 'Producto a eliminar',
            'description' => 'Descripción del producto a eliminar',
            'price' => 49.99,
            'stock' => 5,
        ]);

        $controller = new ProductController();

        $response = $controller->destroy($product->id);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Producto eliminado correctamente', $responseData['message']);

        $deletedProduct = Product::find($product->id);
        $this->assertNull($deletedProduct, 'El producto debe haber sido eliminado de la base de datos');

        if ($deletedProduct) {
            $deletedProduct->delete();
        }
    }

    public function testUpdateMethodUpdatesProduct()
    {
        $product = Product::create([
            'name' => 'Producto a actualizar',
            'description' => 'Descripción del producto a actualizar',
            'price' => 39.99,
            'stock' => 10,
        ]);

        $controller = new ProductController();

        $requestData = [
            'name' => 'Producto actualizado',
            'description' => 'Nueva descripción del producto',
            'price' => 49.99,
            'stock' => 15,
        ];

        $request = new Request($requestData);

        $response = $controller->update($request, $product->id);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Producto actualizado correctamente', $responseData['message']);

        $updatedProduct = Product::find($product->id);
        $this->assertEquals('Producto actualizado', $updatedProduct->name);
        $this->assertEquals('Nueva descripción del producto', $updatedProduct->description);
        $this->assertEquals(49.99, $updatedProduct->price);
        $this->assertEquals(15, $updatedProduct->stock);

        if ($updatedProduct) {
            $updatedProduct->delete();
        }

        
    }
    
}




