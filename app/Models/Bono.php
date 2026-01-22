<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;


use App\Models\PatientBono;

class Bono extends Model
{
 
    use SoftDeletes;

    protected $fillable = [
      'name',
      'price',
      'session_duration',
      'sessions',
      'active'
    ];

    public function patientBonos(){
        return $this->hasMany(PatientBono::class, 'bono_id');
    }


}
