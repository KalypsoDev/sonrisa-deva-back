<?php

namespace Tests\Unit;

use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

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
    }}

