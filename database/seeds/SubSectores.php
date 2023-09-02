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
        $file=json_decode(Storage::disk('local')->get('subsectores.json'));
        $sector=new SubSector();
        $datos=array();
        
        foreach ($file as $key => $value) {       
            $datos[]=[
                'id'=>$value->id,
                'sector_id'=>$value->sector_id,
                'subsector'=>$value->subsector,
            ];
        }        
        $sector->insert($datos);
    }
}
