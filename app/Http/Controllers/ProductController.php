<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $product = Product::all();
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'stock' => 'required|numeric',
                // 'image_url' => 'required|string',
                // 'public_id' => 'required|string',
            ]);

            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                // 'image_url' => $request->image_url,
                // 'public_id' => $request->public_id,
            ]);
            return response()->json([
                'message' => 'El producto se ha creado correctamente',
                'event' => $product,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::find($id);
        
            if (!$product) {
                return response()->json(['error' => 'No se ha encontrado el producto'], 404);
            }
        
            return response()->json(['data' => $product], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Se produjo un error al procesar la solicitud: ' . $e->getMessage()], 500);
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'stock' => 'required|numeric',
                // 'image_url' => 'required|string',
                // 'public_id' => 'required|string',
            ]);

            $product->update($request->all());

            return response()->json(['message' => 'Producto actualizado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);

            if (!$product) {
                return response()->json(['message' => 'Producto no encontrado'], 404);
            }

            $product->delete();

            return response()->json(['message' => 'Producto eliminado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
