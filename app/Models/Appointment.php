<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;


use App\Models\Room;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\Therapist;
use App\Models\AppointmentRecord;
use App\Models\PatientBono;
use App\Models\Bono;

class Appointment extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'room_id',
        'patient_id',
        'treatment_id',
        'therapist_id',
        'appointment_date',
        'duration',
        'status',
        'is_paid',
        'patient_bono_id'
    ];

    public function room(): BelongsTo 
    {
        return $this->belongsTo(Room::class);
    }

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class);
    }

    public function therapist(): BelongsTo 
    {
        return $this->belongsTo(Therapist::class);
    }

    public function patient(): BelongsTo 
    {
        return $this->belongsTo(Patient::class);
    }

    public function bonos(): HasManyThrough
    {
        return $this->hasManyThrough(PatientBono::class, Patient::class, 'id', 'patient_id', 'patient_id', 'id');
    }

    public function patientBono(): BelongsTo
    {
        return $this->belongsTo(PatientBono::class, 'patient_bono_id');
    }

    public static function getFreeHours($therapist_id, $date, $treatment_duration): array
    {
        $appointments = Appointment::where('therapist_id', $therapist_id)
            ->whereDate('appointment_date', $date)
            ->orderBy('appointment_date')
            ->get();

        $workStart = Carbon::parse($date . ' 09:00');
        $workEnd   = Carbon::parse($date . ' 20:00');

        $slotInterval = 15; // minutos
        $freeSlots = [];

        for ($slotStart = $workStart->copy();
            $slotStart->lt($workEnd);
            $slotStart->addMinutes($slotInterval)) {

            $slotEnd = $slotStart->copy()->addMinutes($treatment_duration);

            if ($slotEnd->gt($workEnd)) {
                break;
            }

            $isAvailable = true;

            foreach ($appointments as $appointment) {
                $appStart = Carbon::parse($appointment->appointment_date);
                $appEnd   = $appStart->copy()->addMinutes($appointment->duration);

                if (
                    $slotStart->lt($appEnd) &&
                    $slotEnd->gt($appStart)
                ) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $freeSlots[] = $slotStart->format('H:i');
            }
        }

        return $freeSlots;
    }

    
}