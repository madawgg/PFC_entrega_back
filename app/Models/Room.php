<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Appointment;

class Room extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $casts = [
        'equipment' => 'array',
    ];
    
    protected $fillable = [
        'name',
        'equipment',
        'place',
    ];

    public function appointment(): HasOne
    {
        return $this->hasOne(Appointment::class);
    }
}
