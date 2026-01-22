<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Bono;

class BonoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bonos = [
            [
                'name' => 'Bono 5 sesiones de 30 minutos',
                'price' => 157.50,
                'sessions' => 5,
                'active' => true,
                'session_duration' => 30
            ],
            [
                'name' => 'Bono 10 sesiones de 30 minutos',
                'price' => 297.50,
                'sessions' => 10,
                'active' => true,
                'session_duration' => 30
            ],
            [
                'name' => 'Bono 5 sesiones de 45 minutos',
                'price' => 215,
                'sessions' => 5,
                'active' => true,
                'session_duration' => 45
            ],
            [
                'name' => 'Bono 10 sesiones de 45 minutos',
                'price' => 382.50,
                'sessions' => 10,
                'active' => true,
                'session_duration' => 45
            ],
            [
                'name' => 'Bono 5 sesiones de 60 minutos',
                'price' => 270,
                'sessions' => 5,
                'active' => true,
                'session_duration' => 60
            ],
            [
                'name' => 'Bono 10 sesiones de 60 minutos',
                'price' => 510,
                'sessions' => 10,
                'active' => true,
                'session_duration' => 60
            ]
        ]; 

        foreach($bonos as $bono){
            Bono::create($bono);
        }
    }
}
