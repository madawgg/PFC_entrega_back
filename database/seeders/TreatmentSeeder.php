<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Treatment;
use App\Models\Room;
use App\Models\Therapist;


class TreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        $treatments =[
            [
                'name' => "Sesión de bienvenida",
                'description' => 'Primera sesión con valoración inicial',
                'price' => 50,
                'duration' => 50,
            ],
            [
                'name' => 'Sesión 30',
                'description' => 'Sesión de 30 minutos.',
                'price' => 35.00,
                'duration' => 30
            ],
            [
                'name'=> 'Sesión 45',
                'description' => 'Sesión de 45 minutos.',
                'price' => 45.00,
                'duration' => 45
            ],
            [
                'name' => 'Sesión 60',
                'description' => 'Sesión de 60 minutos.',
                'price' => 60,
                'duration' => 60
            ]
        ];

        foreach ($treatments as $treatment){
            Treatment::create($treatment);
        }
    }
}
