<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Patient;
use App\Models\Therapist;
use App\Models\Admin;
use App\Models\Bono;
use App\Models\Appointment;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'surname',
        'dni',
        'birthdate',
        'phone',
        'address',
        'email',
        'password'
    ];
    protected $casts = [
        'birthdate' => 'date',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function patient(): HasOne
    {
        return $this->hasOne(Patient::class);
    }

    public function appointments()
    {
        return $this->hasManyThrough(Appointment::class, Patient::class, 'user_id', 'patient_id');
    }

    public function therapist(): HasOne 
    {
        return $this->hasOne(Therapist::class);
    }

    public function admin(): HasOne {
        return $this->hasOne(Admin::class);
    }
    public function patientBonos()
    {
        return $this->hasManyThrough(PatientBono::class, Patient::class, 'user_id', 'patient_id');
    }

    public function getRoles()
    {
        $this->load(['admin', 'therapist', 'patient']);

        $roles = [];

        if ($this->admin()->exists()) $roles[] = 'admin';
        if ($this->therapist()->exists()) $roles[] = 'therapist';
        if ($this->patient()->exists()) $roles[] = 'patient';

        return empty($roles) ? ['unknown'] : $roles;
    }
   public function hasRole($role)
    {
        return in_array($role, $this->getRoles());
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
