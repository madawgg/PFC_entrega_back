<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Therapist;

class Specialty extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'description',
        'details'
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function therapists(): BelongsToMany
    {
        return $this->belongsToMany(Therapist::class, 'specialty_therapists');
    }
}
