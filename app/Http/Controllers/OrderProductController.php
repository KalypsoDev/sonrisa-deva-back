<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orderProduct = OrderProduct::all();
            return response()->json($orderProduct);
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

            $product = Product::findOrFail($request->product_id);
            $productPrice = $product->price;

            $unitQuantity = $request->unit_quantity;
            $unitTotalPrice = $productPrice * $unitQuantity;

            $orderProduct = OrderProduct::create([
                'product_id' => $request->product_id,
                'order_id' => $request->order_id,
                'unit_total_price' => $unitTotalPrice,
                'unit_quantity' => $unitQuantity,
            ]);

            $order = Order::findOrFail($request->order_id);
            $totalQuantity = $order->ordersProducts()->sum('unit_quantity');
            $totalPrice = $order->ordersProducts()->sum('unit_total_price');

            $order->update([
                'total_quantity' => $totalQuantity,
                'total_price' => $totalPrice,
            ]);

            return response()->json(['message' => 'Producto agregado al pedido exitosamente', 'product_order' => $orderProduct], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error desconocido: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
