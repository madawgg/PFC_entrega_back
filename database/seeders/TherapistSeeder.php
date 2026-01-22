<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Therapist;
use App\Models\User;
use App\Models\Patient;

class TherapistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::findOrFail(2);
        Patient::create([
            'user_id' => $user->id,
        ]);
        Therapist::create([
            'user_id' => $user->id,
            'career' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium temporibus explicabo est quisquam tenetur rerum nemo ad vitae, vel amet natus delectus assumenda deserunt placeat atque provident eum. Sit, porro.',
            'accreditation' => '123abc',
        ]);
    }
}
