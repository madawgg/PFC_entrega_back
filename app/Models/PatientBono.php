<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Realations\BelongsTo;
use Illuminate\Database\Eloquent\Realations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Bono;
use App\Models\Appointment;
use App\Models\Patient;

class PatientBono extends Model
{
   use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'bono_id',
        'session_id',
        'sessions_total',
        'sessions_used',
        'sessions_remaining',
        'purchase_date',
        'status',
        'expiration_date'
    ];


    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function bono() 
    {
        return $this->belongsTo(Bono::class, 'bono_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // ====================
    // lÓGICA DE MODELO
    // ====================
    
    /**
     * Consumir 1 sesión del bono
     */
    public function useSession(): bool
    {
        if ($this->sessions_remaining <= 0) {
            return false; // No quedan sesiones
        }

        $this->sessions_used++;
        $this->sessions_remaining--;

        if ($this->sessions_remaining === 0) {
            $this->status = 'completed';
        }

        $this->save();
        return true;
    }

    /**
     * Comprobar si el bono tiene sesiones disponibles
     */
    public function hasSessions(): bool
    {
        return $this->sessions_remaining > 0 && $this->status === 'active';
    }

    /**
     * Marcar el bono como expirado
     */
    public function expire()
    {
        $this->status = 'expired';
        $this->save();
    }
 
    /**
     * Obtener porcentaje de uso
     */
    public function usagePercentage(): float
    {
        if ($this->sessions_total === 0) return 0;

        return ($this->sessions_used / $this->sessions_total) * 100;
    }
}
