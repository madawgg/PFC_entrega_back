<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Terapia manual - 1',
                'equipment' => ['Camilla'],
                'place' => 'Bloque 2'
            ],
            [
                'name' => 'Terapia manual - 2',
                'equipment' => ['Camilla'],
                'place' => 'Bloque 2'
            ],
            [
                'name' => 'Terapia manual - 3',
                'equipment' => ['Camilla'],
                'place' => 'Bloque 2'
            ],
            [
                'name' => 'Pruebas',
                'equipment' => [
                    'Maquina de ecografía', 
                    'Pantalla de resultados radiografía'],
                'place' => 'Bloque 2'
            ],
            [
                'name' => 'Primera visita',
                'equipment' => [
                    'Camilla', 
                    'Barras de sujeción', 
                    'Silla'],
                'place' => 'Bloque recepción'
            ],
            [
                'name' => 'Geriátrica, pediátrica y neurológica',
                'equipment' => [
                    'Bastones, andadores, muletas',
                    'Escaleras y barras de ejercicios',
                    'Pesas livianas / bandas elásticas',
                    'Sillas ergonómicas y de transferencia',
                    'Plataformas de equilibrio',
                    'Tensiómetro y oxímetro',
                    'Colchonetas y rampas de foam',
                    'Pelotas Bobath de diferentes tamaños',
                    'Juegos sensoriomotores',
                    'Rodillos, cilindros y túneles',
                    'Espejos de estimulación visual',
                    'Material de psicomotricidad',
                    'Cinturones y arneses de sostén',
                    'Espejos de feedback visual',
                    'Pelotas Bobath',
                    'Barras paralelas',
                    'Plataformas de equilibrio y discos de propiocepción',
                    'Cinta de marcha con soporte',
                    'Material sensorial (texturas, objetos de agarre)'
                ],
                'place' => 'Bloque 3'
            ],
            [
                'name' => 'Deportiva, musculoesquelética y salud laboral',
                'equipment' => [
                    'Camilla',
                    'Bandas elásticas y balones medicinales',
                    'Kinesiotape y vendajes funcionales',
                    'TENS, ultrasonido y crioterapia',
                    'Pistola de masaje o percusión',
                    'Plataforma vibratoria',
                    'Discos de equilibrio / Bosu',
                    'Camilla ajustable o eléctrica',
                    'Bandas elásticas y tubos de resistencia',
                    'Pesas pequeñas / Mancuernas',
                    'Balones terapéuticos',
                    'Rolo y foam roller',
                    'Electroestimulador',
                    'Compresas calientes y frías',
                    'Cintas de kinesiología',
                    'Terapia con láser de baja frecuencia'
                ],
                'place' => 'Gimnasio'
            ],
            [
                'name' => 'Cardiovascular y oncológica',
                'equipment' => [
                    'Bicicleta estática',
                    'Cinta de marcha',
                    'Monitores de ritmo cardíaco y presión arterial',
                    'Espirómetro o medidor de esfuerzo',
                    'Pulsioxímetro y cronómetro',
                    'Material de resistencia progresiva',
                    'Steps y colchonetas para entrenamiento controlado',
                    'Cojines de apoyo y relajación',
                    'Mangas de compresión'
                ],
                'place' => 'Bloque 3'
            ],
            [
                'name' => 'Salud femenina y obstétrica',
                'equipment' => [
                    'Electroestimulador de suelo pélvico',
                    'Balones de pilates',
                    'Pesas pélvicas (cones)',
                    'Espejos y material de control postural',
                    'Cojines de posicionamiento'
                ],
                'place' => 'Bloque 1'
            ],
            [
                'name' => 'Acuática',
                'equipment' => [
                    'Piscina terapéutica climatizada',
                    'Flotadores y churros acuáticos',
                    'Mancuernas y tablas de flotación',
                    'Cinturones de flotación'
                ],
                'place' => 'Gimnasio'
            ],
        ];

        

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
