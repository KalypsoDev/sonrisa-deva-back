<?php

namespace App\Http\Controllers;

use App\Models\OrderProduct;
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
            // Validar los datos del formulario
            // $request->validate([
            //     'order_id' => 'required|exists:orders,id',
            //     'product_id' => 'required|exists:products,id',
            //     'unit_quantity' => 'required|integer|min:1',
            //     'unit_total_price' => 'required|numeric|min:0',
            // ]);

            // Crear un nuevo registro en la tabla product_orders
            $orderProduct = OrderProduct::create([
                'product_id' => $request->product_id,
                'order_id' => $request->order_id,
                'unit_total_price' => $request->unit_total_price,
                'unit_quantity' => $request->unit_quantity,
            ]);

            // Calcular total_quantity y total_price para la orden asociada
            $order = $orderProduct->order;
            $totalQuantity = $order->ordersProducts()->sum('unit_quantity');
            $totalPrice = $order->ordersProducts()->sum('unit_total_price');

            // Actualizar la orden con total_quantity y total_price
            $order->total_quantity = $totalQuantity;
            $order->total_price = $totalPrice;
            $order->save();

            // Retornar una respuesta JSON con el pedido de producto creado
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
