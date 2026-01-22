<?php

namespace App\Http\Controllers\Api;

use App\Models\MedicalHistory;
use Illuminate\Http\Request;
use App\Models\Patient;

class MedicalHistoryController
{

    /**
     * Display a listing of the resource.
     */
    public function getUserMedicalHistories(Patient $patient)
    {

        $medicalHistories = MedicalHistory::where('patient_id', $patient->id)
        ->with('patient.user', 'therapist.user')->get();

        return response()->json([
            'status' => 'success',
            'data' => $medicalHistories
        ], 200);
    }

    /**
     * Display a listing of the resource for the authenticated patient.
     */

    public function getSelfMedicalHistories()
    {
        $medicalHistories = (new MedicalHistory)->getSelfMedicalHistories()
        ->load('therapist.user', 'patient.user');

        return response()->json([
            'status' => 'success',
            'data' => $medicalHistories
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'therapist_id' => 'required|exists:therapists,id',
            'note' => 'required|string',
        ]);

        $medicalHistory = MedicalHistory::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $medicalHistory
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalHistory $medicalHistory)
    {
        $medicalHistory->load('patient.user', 'therapist.user');

        return response()->json([
            'status' => 'success',
            'data' => $medicalHistory
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalHistory $medicalHistory)
    {
        $validated = $request->validate([
            'patient_id' => 'sometimes|exists:patients,id',
            'therapist_id' => 'sometimes|exists:therapists,id',
            'note' => 'sometimes|string',
        ]);

        $medicalHistory->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $medicalHistory
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalHistory $medicalHistory)
    {
        $medicalHistory->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Registro de historial médico eliminado correctamente'
        ]);
    }
}
