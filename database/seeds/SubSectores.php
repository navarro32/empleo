<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\SubSector;

class SubSectores extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = json_decode(Storage::disk('local')->get('subsectores.json'));
        $sector = new SubSector();
        $datos = array();

        $batchSize = 100; // Tamaño del lote (ajusta según sea necesario)

        foreach (array_chunk($file, $batchSize) as $batch) {
            $batchData = [];

            foreach ($batch as $value) {
                $batchData[] = [
                    'id' => $value->id,
                    'sector_id' => $value->sector_id,
                    'subsector' => $value->subsector,
                ];
            }

            $sector->insert($batchData);
        }
    }
}
