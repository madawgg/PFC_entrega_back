<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Models\Specialty;

class SpecialtyController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $specialties = Specialty::all();
            return response()->json([
                'status' => 'success',
                'data' => $specialties
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:250',
            'description' => 'required|string|max:3000',
            'details' => 'sometimes|array',
            'details.*' => 'string|max:400',
        ]);

        try {

            $specialty = Specialty::create($validated);

            return response()->json([
                'status' => 'success',
                'data' => $specialty
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo asignar al terapeuta',
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
            $specialty = Specialty::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $specialty
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener la especialidad',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $specialty = Specialty::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:250',
            'description' => 'sometimes|string|max:3000',
            'details' => 'sometimes|array',
            'details.*' => 'string|max:400',
        ]);

        try {

            $specialty->update($validated);

            return response()->json([
                'status' => 'success',
                'data' => $specialty
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo actualizar la especialidad',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $specialty = Specialty::find($id);

        if (! $specialty) {
            return response()->json([
                'message' => 'Especialidad no encontrada'
            ], 404);
        }

        $specialty->delete();

        return response()->json([
            'message' => 'Especialidad eliminada correctamente'
        ], 200);
    }

    
}
