<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
                'image_url' => 'required|image',
                'location' => 'required|string',
                'collection' => 'nullable|numeric',
                'date' => 'nullable|date',
                'hour' => 'nullable|date_format:H:i'
            ]);

            $file = $request->file('image_url');
            $cloudinaryUpload = Cloudinary::upload($file->getRealPath(), ['folder' => 'sonrisa']);

            $public_id = $cloudinaryUpload->getPublicId();
            $url = $cloudinaryUpload->getSecurePath();

            $event = Event::create([
                'title' => $request->title,
                'image_url' => $url,
                'public_id' => $public_id,
                'location' => $request->location,
                'collection' => $request->collection,
                'date' => $request->date,
                'hour' => $request['hour'],
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
            $event = Event::findOrFail($id);

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

            $url = $event->image_url;
            $public_id = $event->public_id;

            $request->validate([
                'title' => 'required|string|max:255',
                'image_url' => 'required|image',
                'location' => 'required|string',
                'collection' => 'nullable|numeric',
                'date' => 'nullable|date',
                'hour' => 'nullable|date_format:H:i'
            ]);

            if ($request->hasFile('image_url')) {
                Cloudinary::destroy($public_id);
                $file = $request->file('image_url');
                $cloudinaryUpload = Cloudinary::upload($file->getRealPath(), ['folder' => 'sonrisa']);

                $url = $cloudinaryUpload->getSecurePath();
                $public_id = $cloudinaryUpload->getPublicId();
            }

            $event->update([
                "title" => $request->title,
                "image_url" => $url,
                "location" => $request->location,
                "collection" => $request->collection,
                "date" => $request->date,
                "hour" => $request['hour'],
                "public_id" => $public_id
            ]);

            return response()->json(['message' => 'Evento actualizado correctamente', $event], 200);
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

            return response()->json(['message' => 'Evento eliminado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
