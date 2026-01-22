<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Patient;
use App\Models\Therapist;

class MedicalHistory extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'patient_id',
        'therapist_id',
        'note',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function therapist()
    {
        return $this->belongsTo(Therapist::class);
    }

    public function getSelfMedicalHistories()
    {
        $patient = auth()->user()->patient;
        return self::where('patient_id', $patient->id)->get();
    }
}
