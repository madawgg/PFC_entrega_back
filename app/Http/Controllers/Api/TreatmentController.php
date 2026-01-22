<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Models\Treatment;

class TreatmentController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $treatments = Treatment::all();

        return response()->json([
            'status' => 'success',
            'data' => $treatments
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $validated = $request->validate([
            'name'=> 'required|string|max:255',
            'description' => 'required|string|max:3000',
            'price' => 'required|numeric',
            'duration' => 'required|integer'
        ]);

        try {

            $treatment = Treatment::create($validated);

            return response()->json([
                'status' => 'success',
                'data' => $treatment
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo guardar el tratamiento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $treatment = Treatment::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $treatment
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el tratamiento',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $treatment = Treatment::findOrFail($id);

        $validated = $request->validate([
            'name' =>'sometimes|string|max:256',
            'description' => 'sometimes|string|max:3000',
            'price' => 'sometimes|numeric', 
            'duration' => 'sometimes|integer'
        ]);

        try {

            $treatment->update($validated);

            return response()->json([
                'status' => 'success',
                'data' => $treatment
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo guardar el tratamiento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $treatment = Treatment::find($id);

        if (! $treatment) {
            return response()->json([
                'message' => 'Tratamiento no encontrado'
            ], 404);
        }

        $treatment->delete();

        return response()->json([
            'message' => 'Tratamiento eliminado correctamente'
        ], 200);
    }
    
    
}
