<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Specialty;
use App\Models\Therapist;

class SpecialtyTherapistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $therapist = Therapist::find(1);
        $therapist->specialties()->attach(1);
    }
}
