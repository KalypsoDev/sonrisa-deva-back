<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
                'image_url' => 'required|image',
            ]);

            $file = $request->file('image_url');
            $cloudinaryUpload = Cloudinary::upload($file->getRealPath(), ['folder' => 'sonrisa']);

            $public_id = $cloudinaryUpload->getPublicId();
            $url = $cloudinaryUpload->getSecurePath();

            $product = Product::create([
                "name" => $request->name,
                "description" => $request->description,
                "price" => $request->price,
                "stock" => $request->stock,
                "image_url" => $url,
                "public_id" => $public_id
            ]);

            return response()->json([
                'message' => 'El producto se ha creado correctamente',
                'product' => $product,
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

            $url = $product->image_url;
            $public_id = $product->public_id;

            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'stock' => 'required|numeric',
                'image_url' => 'required|image',
            ]);

            if ($request->hasFile('image_url')) {
                Cloudinary::destroy($public_id);
                $file = $request->file('image_url');
                $cloudinaryUpload = Cloudinary::upload($file->getRealPath(), ['folder' => 'sonrisa']);

                $url = $cloudinaryUpload->getSecurePath();
                $public_id = $cloudinaryUpload->getPublicId();
            }

            $product->update([
                "name" => $request->name,
                "description" => $request->description,
                "price" => $request->price,
                "stock" => $request->stock,
                "image_url" => $url,
                "public_id" => $public_id
            ]);

            return response()->json(['message' => 'Producto actualizado correctamente', $product], 200);
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
