<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $event = Event::all();
            return response()->json($event);
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
                'title' => 'required|string|max:255',
                // 'image_url' => 'required|string',
                // 'public_id' => 'required|string',
                'description' => 'nullable|string',
                'location' => 'required|string',
                'collection' => 'nullable|numeric',
                'start_date' => 'required|date',
            ]);

            $event = Event::create([
                'title' => $request->title,
                'description' => $request->description,
                // 'image_url' => $request->image_url,
                // 'public_id' => $request->public_id,
                'location' => $request->location,
                'collection' => $request->collection,
                'start_date' => $request->start_date,
            ]);
            return response()->json([
                'message' => 'El evento se ha creado correctamente',
                'event' => $event,
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
            $event = Event::find($id);
        
            if (!$event) {
                return response()->json(['error' => 'No se ha encontrado el evento'], 404);
            }
        
            return response()->json(['data' => $event], 200);
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
            $event = Event::findOrFail($id);
            $request->validate([
                'title' => 'required|string|max:255',
                // 'image_url' => 'required|string',
                // 'public_id' => 'required|string',
                'description' => 'nullable|string',
                'location' => 'required|string',
                'collection' => 'nullable|numeric',
                'start_date' => 'required|date',
            ]);

            $event->update($request->all());

            return response()->json(['message' => 'Evento actualizado correctamente'], 200);
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
            $event = Event::findOrFail($id);

            if (!$event) {
                return response()->json(['message' => 'Evento no encontrado'], 404);
            }

            $event->delete();

            return response()->json(['message' => 'Producto eliminado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
