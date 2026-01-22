<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Therapist;
use App\Models\Appointment;
use App\Models\Treatment;
use App\Models\PatientBono;
use App\Models\Bono;
use Carbon\Carbon;

class AppointmentController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
    $appointments = Appointment::with(['patient.user','therapist.user','treatment','room','bonos','bonos'])->get();

    $formatted = $appointments->map(function($a) {
        return [
            'id' => $a->id,
            'appointment_date' => $a->appointment_date,
            'duration' => $a->duration,
            'status' => $a->status,
            'is_paid' => $a->is_paid,
            'bonos' => [
                'id' => $a->patientBonos?->id,
                'name' => $a->patientBonos?->name,
                'remaining_sessions' => $a->patientBonos?->sessions_remaining,
            ],
            'room' => [
                'id' => $a->room?->id,
                'name' => $a->room?->name ?? "Sala {$a->room?->id}",
            ],
            'patient' => [
                'id' => $a->patient->id,
                'full_name' => $a->patient->full_name,
            ],
            'therapist' => [
                'id' => $a->therapist?->id,
                'full_name' => $a->therapist?->full_name,
            ],
            'treatment' => [
                'id' => $a->treatment?->id,
                'description' => $a->treatment?->description,
                'price' => $a->treatment?->price,
                'duration' => $a->treatment?->duration,
            ],
        ];
    });

    return response()->json([
        'status' => 'success',
        'data' => $formatted
    ], 200);
    }


    /**
     * Get free hours for a therapist on a specific date.
     */
    public function getFreeHours(Request $request)
    {
        $validated = $request->validate([
            'therapist_id' => 'required|exists:therapists,id',
            'date' => 'required|date',
            'treatment_id' => 'required|exists:treatments,id',
        ]);

        $treatment = Treatment::findOrFail($validated['treatment_id']);
       
        $freeHours = Appointment::getFreeHours(
            $validated['therapist_id'],
            $validated['date'],
            $treatment->duration
        );

        return response()->json([
            'status' => 'success',
            'data' => $freeHours
        ], 200);
    }

    /**
     * Purchase an appointment (for patients).
     */

    public function purchaseAppointment(Request $request)
    {
            $validated = $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'therapist_id' => 'nullable|exists:therapists,id',
            'room_id' => 'nullable|exists:rooms,id',
            'treatment_id' => 'required|exists:treatments,id',
            'appointment_date' => 'required|date_format:Y-m-d H:i:s|after_or_equal:now',
            'status' => 'nullable|in:pending,confirmed,completed,cancelled',
            'is_paid' => 'nullable|boolean',
            'patient_bono_id' => 'nullable|exists:patient_bonos,id',
        ]);

        try {
            $appointment = DB::transaction(function() use ($validated) {
                $user = auth()->user();

                if ($user->hasRole('patient') && count($user->getRoles()) === 1) {
                    $validated['patient_id'] = $user->patient->id;
                    $validated['status'] = 'pending';
                    $validated['is_paid'] = true;
                }

                $treatment_duration = Treatment::findOrFail($validated['treatment_id'])->duration;
                
                
                // Validar bono (si viene)
                  if (!empty($validated['patient_bono_id'])) {
                    $patientBono = PatientBono::find($validated['patient_bono_id']);

                    if (!$patientBono || !$patientBono->hasSessions()) {
                        throw new \Exception(
                            'El bono seleccionado no tiene sesiones disponibles o está caducado.'
                        );
                    }

                    // Descontar sesión
                    $patientBono->useSession();
                }

                return Appointment::create([
                    'patient_id' => $validated['patient_id'],
                    'therapist_id' => $validated['therapist_id'] ?? null,
                    'room_id' => $validated['room_id'] ?? null,
                    'treatment_id' => $validated['treatment_id'] ?? null,
                    'appointment_date' => $validated['appointment_date'],
                    'duration' => $treatment_duration,
                    'status' => $validated['status'] ?? 'pending',
                    'is_paid' => true,
                    'patient_bono_id' => $validated['patient_bono_id'] ?? null,
                ]);
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Cita agendada correctamente',
                'data' => $appointment
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al procesar el pago',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'therapist_id' => 'sometimes|integer|exists:therapists,id',
            'room_id' => 'sometimes|integer|exists:rooms,id',
            'patient_id' => 'required|integer|exists:patients,id',
            'treatment_id' => 'required|integer|exists:treatments,id',
            'appointment_date' => 'required|date_format:Y-m-d H:i:s|after_or_equal:now',
            'patient_bono_id' => 'sometimes',
        ]);

        try {
            $appointment = DB::transaction(function () use ($validated) {

                // Obtener tratamiento y duración
                $treatment = Treatment::findOrFail($validated['treatment_id']);
                $validated['duration'] = $treatment->duration;

                // Validar bono (si viene)
                if (!empty($validated['patient_bono_id'])) {
                    $patientBono = PatientBono::find($validated['patient_bono_id']);

                    if (!$patientBono || !$patientBono->hasSessions()) {
                        throw new \Exception(
                            'El bono seleccionado no tiene sesiones disponibles o está caducado.'
                        );
                    }

                    // Descontar sesión
                    $patientBono->useSession();
                }

                // Crear cita
                return Appointment::create([
                    'patient_id' => $validated['patient_id'],
                    'therapist_id' => $validated['therapist_id'] ?? null,
                    'room_id' => $validated['room_id'] ?? null,
                    'treatment_id' => $validated['treatment_id'],
                    'appointment_date' => $validated['appointment_date'],
                    'duration' => $validated['duration'],
                    'status' => 'scheduled',
                    'is_paid' => true,
                    'patient_bono_id' => $validated['patient_bono_id'] ?? null,
                ]);
            });

            return response()->json([
                'status' => 'success',
                'data' => $appointment
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se ha podido crear la cita',
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
            $appointment = Appointment::with(['patient.user','therapist.user','treatment','room','patientBono','bonos'])->findOrFail($id);
            $patientBono = $appointment->bonos->first();
            // Formatear los datos igual que en index
            $formatted = [
                'id' => $appointment->id,
                'appointment_date' => $appointment->appointment_date,
                'duration' => $appointment->duration,
                'status' => $appointment->status,
                'is_paid' => $appointment->is_paid,
                'patient_bono_id' => $appointment->patient_bono_id,
                'patient_bono_name' => $patientBono?->bono?->name,
                'room' => [
                    'id' => $appointment->room?->id,
                    'name' => $appointment->room?->name ?? "Sala {$appointment->room_id}",
                ],
                'patient' => [
                    'id' => $appointment->patient?->id,
                    'full_name' => $appointment->patient?->full_name ?? "Paciente desconocido",
                    'user_id' => $appointment->patient?->user?->id,
                ],
                'therapist' => [
                    'id' => $appointment->therapist?->id,
                    'full_name' => $appointment->therapist?->full_name ?? "Terapeuta desconocido",
                    'user_id' => $appointment->therapist?->user?->id,
                ],
                'treatment' => [
                    'id' => $appointment->treatment?->id,
                    'description' => $appointment->treatment?->description ?? "Tratamiento desconocido",
                    'price' => $appointment->treatment?->price,
                    'duration' => $appointment->treatment?->duration,
                    'name' => $appointment->treatment?->name,
                ],
            ];

            return response()->json([
                'status' => 'success',
                'data' => $formatted
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener la cita',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       
        $validated = $request->validate([
            'therapist_id' => 'sometimes|integer|exists:therapists,id',
            'room_id' => 'sometimes|integer|exists:rooms,id',
            'patient_id' => 'sometimes|integer|exists:patients,id',
            'treatment_id' => 'sometimes|integer|exists:treatments,id',
            'appointment_date' => 'sometimes|date_format:Y-m-d H:i:s',
            'duration' => 'sometimes|integer',
            'patient_bono_id' => 'sometimes|nullable|exists:patient_bonos,id',
            'status' => 'sometimes|in:scheduled,completed,cancelled',
            'is_paid' => 'sometimes|boolean',
        ]);

        return DB::transaction(function() use ($request, $id, $validated) {

            $appointment = Appointment::findOrFail($id);

            $previousStatus = $appointment->status;
            $previousPatientBonoId = $appointment->patient_bono_id;

            // Normalizar patient_bono_id según estado
            $statusToUpdate = $validated['status'];

            if ($statusToUpdate !== 'cancelled') {
                // Mantenemos el bono anterior si no se cancela
                $validated['patient_bono_id'] = $previousPatientBonoId;
            } else {
                // Si se cancela, quitar bono de la cita
                $validated['patient_bono_id'] = null;
            }

            // Actualizar la cita
            $appointment->update($validated);

            // Reembolsar sesión si se cancela y había un bono asignado
            if ($previousStatus !== 'cancelled' && $statusToUpdate === 'cancelled' && $previousPatientBonoId) {
                $patientBono = PatientBono::where('id', $previousPatientBonoId)
                    ->lockForUpdate()
                    ->first();

                if ($patientBono) {
                    // Incremento/decremento atómico
                    $patientBono->increment('sessions_remaining', 1);
                    $patientBono->decrement('sessions_used', 1);

                    if ($patientBono->status !== 'active') {
                        $patientBono->update(['status' => 'active']);
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'data' => $appointment
            ], 200);

        }); 
    }

    /**
     * Get free therapists for a given time slot.
     */
    public function getFreeTherapists(Request $request)
    {
        $request->validate([
            'start'    => 'required|date',
            'duration' => 'required|integer|min:1',
        ]);
        $duration = $request->integer('duration');
        $slotStart = Carbon::parse($request->start);
        $slotEnd   = $slotStart->copy()->addMinutes($duration);

        // Terapeutas ocupados en ese intervalo
        $occupiedTherapistIds = Appointment::whereNotNull('therapist_id')
            ->where('status', '!=', 'cancelled')
            ->where('appointment_date', '<', $slotEnd)
            ->whereRaw(
                "DATE_ADD(appointment_date, INTERVAL duration MINUTE) > ?",
                [$slotStart]
            )
            ->pluck('therapist_id')
            ->unique()
            ->toArray();

        // Terapeutas libres
        $freeTherapists = Therapist::whereNotIn('id', $occupiedTherapistIds)->get();

        return response()->json([
            'status' => 'success',
            'data'   => $freeTherapists,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $appointment = Appointment::findOrFail($id);

        $appointment->delete();

        return response()->json([
            'message' => 'Cita eliminada correctamente'
        ], 200);
    }
}
