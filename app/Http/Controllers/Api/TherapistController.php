<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Therapist;


class TherapistController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $therapists = Therapist::with('user', 'specialties')->get();
        $formated = $therapists->map(function($t) {
            return [
                'id' => $t->id,
                'full_name' => $t->full_name,
                'user' => [
                    'id' => $t->user->id,
                    'name' => $t->user->name,
                    'surname' => $t->user->surname,
                    'dni' => $t->user->dni,
                    'email' => $t->user->email,
                ],
                'accreditation' => $t->accreditation,
                'career' => $t->career,
                'specialties' => $t->specialties,
            ];
        });

            return response()->json([
            'status' => 'success',
            'data' => $formated
        ], 200);
    }

    public function getTherapistAppointments($id)
    {
        try {
            $therapist = Therapist::findOrfail($id);
            $appointments = $therapist->getAppointments();
            
            return response()->json([
                'status' => 'success',
                'data' => $appointments
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener las citas del terapeuta',
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'accreditation' => 'string|max:50',
            'career' => 'string|max:3000'
        ]);

        try {

            $therapist = Therapist::create($validated);

            return response()->json([
                'status' => 'success',
                'data' => $therapist
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo asignar al terapeuta',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $therapist = Therapist::findOrFail($id);
            $therapist->load('user', 'specialties', 'appointments');

            return response()->json([
                'status' => 'success',
                'data' => $therapist
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el terapeuta',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $therapist = Therapist::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|integer|exists:users,id',
            'accreditation' => 'sometimes|string|max:50',
            'career' => 'sometimes|string|max:3000'
        ]);

        try {

            $therapist->update($validated);

            return response()->json([
                'status' => 'success',
                'data' => $therapist
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo asignar al terapeuta',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $therapist = Therapist::find($id);

        if (! $therapist) {
            return response()->json([
                'message' => 'Therapist no encontrado'
            ], 404);
        }

        $therapist->delete();

        return response()->json([
            'message' => 'Therapist eliminado correctamente'
        ], 200);
    }
    
    public function getTherapistByUserId($userId)
    {
        try {
            $therapist = Therapist::where('user_id', $userId)->firstOrFail();

            return response()->json([
                'status' => 'success',
                'data' => $therapist
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el terapeuta por user_id',
            ], 404);
        }
    }

    public function getTherapistWithSpecialties($id)
    {
        try {
            $therapist = Therapist::with('specialties')->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $therapist
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el terapeuta con especialidades',
            ], 404);
        }
    }


    public function indexWithSpecialties()
    {
        try {
            $therapists = Therapist::with('specialties')->get();

            return response()->json([
                'status' => 'success',
                'data' => $therapists
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los terapeutas con especialidades',
            ], 404);
        }
    }
}

