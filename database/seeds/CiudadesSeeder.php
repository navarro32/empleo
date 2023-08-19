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
        
        foreach ($file as $key => $value) {  
                    
            $datos[]=[                
                'ciudad'=>$value->ciudad,
                'departamento_id'=>$value->departamento_id,
            ];
        }  
        $citys->insert($datos);
        // $ciudades = collect($datos);
        // $dividido = $ciudades->chunk(100)->toArray();      
        // for ($i=0; $i < count($dividido) ; $i++) { 
        //     $citys->insert($dividido[$i]);           
        // }         
        
    }
}
