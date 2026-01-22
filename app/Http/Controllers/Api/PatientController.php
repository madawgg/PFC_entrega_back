<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Appointment;

class PatientController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::with('user', 'patientBonos', 'medicalHistories')->get();
        $formated = $patients->map(function($p) {
            return [
                'id' => $p->id,
                'full_name' => $p->full_name,
                'user' => [
                    'id' => $p->user->id,
                    'name' => $p->user->name,
                    'surname' => $p->user->surname,
                    'dni' => $p->user->dni,
                    'email' => $p->user->email,
                ],
                'medical_history' => $p->medical_history,
                'patientBonos' => $p->patientBonos,
            ];
        });
        return response()->json([
            'status' => 'success',
            'data' => $formated
        ], 200);
    }

    /**
     * Get appointments for a specific patient.
     */

    public function getPatientAppointments($id)
    {
        try {
            $patient = Patient::findOrfail($id);
            $appointments = $patient->getAppointments();

            return response()->json([
                'status' => 'success',
                'data' => $appointments
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener las citas del paciente',
            ], 404);
        }
    }
    public function getSelfAppointment($appointmentId)
    {
        $appointment = Appointment::with([
            'therapist',
            'treatment',
            'room',
        ])
        ->where('id', $appointmentId)
        ->firstOrFail();
        
        $appointment['patient_bono_name'] = $appointment->patientBono?->bono?->name ?? null;
        $appointment['therapist_full_name'] = $appointment->therapist?->full_name ?? null;
        return response()->json([
            'status' => 'success',
            'data' => $appointment
        ], 200);
    }

    public function getSelfAppointments(Request $request)
    {
        try {
            $user = $request->user();
            $patient = Patient::where('user_id', $user->id)->firstOrFail();
            $appointments = $patient->getAppointments();

            return response()->json([
                'status' => 'success',
                'data' => $appointments
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener las citas del paciente',
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
            'medical_history' => 'string|max:5000',
        ]);

        try {

            $patient = Patient::create($validated);

            return response()->json([
                'status' => 'success',
                'data' => $patient
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo asignar al paciente',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $patient = Patient::findOrFail($id);
            $patient->load('user', 'patientBonos', 'medicalHistories', 'appointments');

            return response()->json([
                'status' => 'success',
                'data' => $patient
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el Paciente',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $patient = Patient::findOrFail($id);
        
        $validated = $request->validate([
            'user_id' => 'sometimes|integer|exists:users,id',
        ]);

        try {

            $patient->update($validated);

            return response()->json([
                'status' => 'success',
                'data' => $patient
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $patient = Patient::find($id);

        if (! $patient) {
            return response()->json([
                'message' => 'Paciente no encontrado'
            ], 404);
        }

        $patient->delete();

        return response()->json([
            'message' => 'Paciente eliminado correctamente'
        ], 200);
    }

}
