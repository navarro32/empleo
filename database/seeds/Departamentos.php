<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Departamento;
class Departamentos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file=json_decode(Storage::disk('local')->get('departamentos.json'));
        $departamento=new Departamento();
        $datos=array();
        
        foreach ($file as $key => $value) {            
            $datos[]=[
                'id'=>$value->id,
                'departamento'=>$value->departamento
            ];
        }        
        $departamento->insert($datos);
    }
}
