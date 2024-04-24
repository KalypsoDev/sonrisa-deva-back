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

    public function updateStatusAndStock(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            // Verifica si el estado de la orden no es "Shipped"
            if ($order->status !== 'Shipped') {
                // Actualiza el estado de la orden a "Shipped"
                $order->status = 'Shipped';
                $order->save();

                // Actualiza el stock de los productos asociados a la orden
                foreach ($order->ordersProducts as $orderProduct) {
                    $product = $orderProduct->product;

                    // Verifica si el producto existe
                    if ($product) {
                        $newStock = $product->stock - $orderProduct->unit_quantity;

                        // Verifica que el stock no sea negativo
                        if ($newStock >= 0) {
                            // Actualiza el stock del producto
                            $product->update(['stock' => $newStock]);
                        } else {
                            // Si el stock es negativo, devuelve un error
                            return response()->json(['error' => 'No hay suficiente stock para el producto'], 400);
                        }
                    } else {
                        // Maneja el caso en el que el producto no existe
                        return response()->json(['error' => 'El producto asociado no existe'], 400);
                    }
                }

                // Devuelve una respuesta de éxito
                return response()->json(['message' => 'Estado de la orden actualizado a "Shipped" y stock de los productos actualizado']);
            } else {
                // Si el estado de la orden ya es "Shipped", devuelve un error
                return response()->json(['error' => 'La orden ya ha sido enviada'], 400);
            }
        } catch (\Exception $e) {
            // Maneja cualquier excepción que pueda ocurrir durante el proceso
            return response()->json(['error' => 'Error al procesar la solicitud: ' . $e->getMessage()], 500);
        }
    }
}
