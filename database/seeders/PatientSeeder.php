<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Patient;
use App\Models\User;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::find(3);
        $patient = [
            'user_id' => $user->id,
        ];

        Patient::create($patient);
        // Traemos todos los usuarios que deben ser pacientes
        $users = User::where('id', '>', 3)->get();

        foreach ($users as $user) {
            Patient::create([
                'user_id' => $user->id,
                // otros campos opcionales
            ]);
        }
    }
}
