<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;
use App\Models\Treatment;
use App\Models\Specialty;
use App\Models\Appointment;
use App\Models\MedicalHistory;

class Therapist extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $appends = ['full_name'];

    protected $fillable = [
        'access_level',
        'accreditation',
        'career',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function appointments(): HasMany 
    {
        return $this->hasMany(Appointment::class);
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class, 'therapist_id');
    }

    public function specialties(): BelongsToMany 
    {
        return $this->belongsToMany(Specialty::class, 'specialty_therapists');
    }
    public function medicalHistories(): HasMany{
        return $this->hasMany(MedicalHistory::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->user->name} {$this->user->surname}";
    }
    
    public function getAppointments()
    {
        return $this->appointments()->with('patient.user', 'treatment', 'room')
            ->orderBy('appointment_date', 'DESC')->get();
    }

}
