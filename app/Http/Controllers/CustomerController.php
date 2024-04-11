<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $customer = Customer::all();
            return response()->json($customer);
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
                'username' => 'required|string|max:255',
                'email' => 'required|string',
                'address' => 'required|string',
                'telephone' => 'required|string',
            ]);

            $customer = Customer::where('email', $request->email)->first();

            if ($customer) {
                return response()->json(['message' => 'Cliente existente encontrado', 'customer' => $customer], 200);
            }

            $customer = Customer::create([
                'username' => $request->username,
                'email' => $request->email,
                'address' => $request->address,
                'telephone' => $request->telephone,
            ]);

            return response()->json([
                'message' => 'El cliente se ha guardado correctamente',
                'customer' => $customer,
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
