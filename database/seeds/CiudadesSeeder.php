<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Ciudades;

class CiudadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file=json_decode(Storage::disk('local')->get('ciudades.json'));
        $citys=new Ciudades();
        $datos=array();
        
        $batchSize = 100; // Tamaño del lote (ajusta según sea necesario)

        foreach (array_chunk($file, $batchSize) as $batch) {
            $batchData = [];
    
            foreach ($batch as $value) {
                $batchData[] = [
                    'ciudad' => $value->ciudad,
                    'departamento_id' => $value->departamento_id,
                ];
            }
    
            $citys->insert($batchData);
        }              
        
    }
}
