<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Sector;
class Sectores extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file=json_decode(Storage::disk('local')->get('sectores.json'));
        $sector=new Sector();
        $datos=array();
        foreach ($file as $key => $value) {            
            $datos[]=[
                'id'=>$value->id,
                'sector'=>$value->sector
            ];
        }        
        $sector->insert($datos);
    }
}
