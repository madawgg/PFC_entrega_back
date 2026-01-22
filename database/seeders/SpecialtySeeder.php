<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $specialties = [
            [
                'name' => 'Musculoesquelética', 
                'description' => 'Rehabilitación de huesos, articulaciones y músculos',
                'details' => [
                    'Terapia manual', 
                    'Rehabilitación post-fractura',
                    'Lesiones deportivas'
                ]
            ],
            [
                'name' => 'Neurológica', 
                'description' => 'Tratamiento de lesiones del sistema nervioso',
                'details' => [
                    'ACV',
                    'Parálisis cerebral', 
                    'Parkinson',
                    'Esclerosis múltiple'
                ]
            ],
            [
                'name' => 'Pediátrica', 
                'description' => 'Cuidado y rehabilitación de niños y adolescentes',
                'details' => [
                    'Retrasos motores',
                    'Escoliosis',
                    'Parálisis cerebral infantil',
                    'Rehabilitación post-trauma'
                ]
            ],
            [
                'name' => 'Geriátrica', 
                'description' => 'Cuidado y rehabilitación de personas mayores',
                'details' => [
                    'Prevención de caídas',
                    'Osteoartritis',
                    'Osteoporosis',
                    'Enfermedades degenerativas'
                ]
            ],
            [
                'name' => 'Deportiva', 
                'description' => 'Optimización del rendimiento y prevención de lesiones en deportistas',
                'details' => [
                    'Prevención y tratamiento de lesiones',
                    'Readaptación funcional',
                    'Masaje deportivo',
                    'Vendajes funcionales'
                ]
            ],
            [
                'name' => 'Cardiovascular', 
                'description' => 'Rehabilitación de enfermedades del corazón y circulación',
                'details' => [
                    'Rehabilitación tras infarto',
                    'Insuficiencia cardíaca',
                    'Prevención de enfermedades cardiovasculares'
                ]
            ],
            [
                'name' => 'Salud femenina y obstétrica', 
                'description' => 'Atención a la salud de la mujer y embarazo',
                'details' => [
                    'Suelo pélvico',
                    'Embarazo y postparto',
                    'Incontinencia urinaria',
                    'Dolor pélvico crónico',
                    'Disfunciones sexuales'
                ]
            ],
            [
                'name' => 'Oncológica', 
                'description' => 'Apoyo a pacientes con cáncer para mejorar calidad de vida',
                'details' => [
                    'Prevención de linfedema',
                    'Rehabilitación post-cirugía', 
                    'Manejo de fatiga',
                    'Manejo del dolor'
                ]
            ],
            [
                'name' => 'Salud laboral', 
                'description' => 'Prevención y tratamiento de lesiones relacionadas con el trabajo',
                'details' => [
                    'Ergonomía',
                    'Lesiones por movimientos repetitivos',
                    'Rehabilitación de espalda y cuello',
                    'Programas preventivos en empresas'
                ]
            ],
            [
                'name' => 'Acuática', 
                'description' => 'Uso del agua para rehabilitación',
                'details' => [
                    'Mejorar movilidad y fuerza con menor impacto en articulaciones',
                    'Rehabilitación de artritis', 
                    'Rehabilitación neurológica'
                ]
            ],
        ];

        foreach ($specialties as $specialty) {
            Specialty::create($specialty);
        }
    }
}
