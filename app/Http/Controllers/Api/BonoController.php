<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Models\Bono;

class BonoController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    
        $bonos = Bono::all();
        
        return response()->json([
            'status' => 'success',
            'data' => $bonos
        ], 200);
    
    }
    public function indexWithTrashed()
    {
    
        $bonos = Bono::withTrashed()->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $bonos
        ], 200);
    
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'sessions' => 'required|integer',
            'session_duration' => 'required|integer',
            'active' => 'required|boolean',
        ]);

        $bono = Bono::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $bono
        ], 201);

      
    }

    /**
     * Display the specified resource.
     */
    public function show(Bono $bono)
    {
       return response()->json([
            'status' => 'success',
            'data' => $bono
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bono $bono)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'sessions' => 'sometimes|required|integer',
            'active' => 'sometimes|required|boolean',
        ]);
        $bono->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $bono
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bono $bono)
    {
   
        $bono->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Bono eliminado correctamente'
        ], 200);

    }

    public function restore(string $id)
    {
        $bono = Bono::withTrashed()->find($id);

        if (! $bono) {
            return response()->json([
                'message' => 'Bono no encontrado'
            ], 404);
        }

        $bono->restore();

        return response()->json([
            'message' => 'Bono restaurado correctamente'
        ], 200);
    }
}
