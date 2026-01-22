<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Appointment;

class Treatment extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'price',
        'description',
    ];
    
    public function appointment(): HasMany 
    {
        return $this->hasMany(Appointment::class);
    }

 
   
}
