<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;


use App\Models\Admin;

class AdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::all();
        
        return response()->json([
            'status' => 'success',
            'data' => $admins
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        try {

            $admin = Admin::create($validated);

            return response()->json([
                'status' => 'success',
                'data' => $admin
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo asignar al admin',
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
            $admin = Admin::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $admin
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el Administrador',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = Admin::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|integer|exists:users,id',
            'access_level' => 'sometimes|integer|in:0,1'
        ]);
        
        try {

            $admin->update($validated);

            return response()->json([
                'status' => 'success',
                'data' => $admin
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo actualizar el Admin',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = Admin::find($id);

        if (! $admin) {
            return response()->json([
                'message' => 'Admin no encontrado'
            ], 404);
        }

        $admin->delete();

        return response()->json([
            'message' => 'Admin eliminado correctamente'
        ], 200);
    }
}
