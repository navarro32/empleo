<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Area;

class Areas extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file=json_decode(Storage::disk('local')->get('areas.json'));
        $citys=new Area();
        $datos=array();
        
        foreach ($file as $key => $value) {                    
            $datos[]=[                
                'area'=>$value->area
            ];
        }  
        $citys->insert($datos);
    }
}
