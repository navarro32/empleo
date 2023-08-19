<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Departamento;
use App\Models\Ciudades;
use App\Models\Sector;
use App\Models\SubSector;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    /**
     * Obtener las areas registradas en base de datos.
     *     
     */
    public function getAreas()
    {
       $areas=new Area();
       return response()->json($areas->all());
    }
    /**
     * Obtener los departamentos registradas en base de datos.
     *     
     */
    public function getDepartamentos()
    {
       $dep=new Departamento();
       return response()->json($dep->all());
    }
    /**
     * Obtener las ciudades registradas en base de datos.
     *     
     */
    public function getCiudades(Request $request)
    {
       $city=new Ciudades();
       $citys= $city->where('departamento_id', $request->id)->get();      
       return response()->json($citys);
    }
     /**
     * Buscar la ciudad
     *     
     */
    public function buscarCiudad(Request $request){
        $city=new Ciudades();
        $citys= $city->where('ciudad', 'LIKE', $request->valor.'%')->limit(5)->with('departamento')->get()->map(function($query){
            $data['id']=$query->id;
            $data['ciudad']=$query->ciudad;
            $data['departamento']=$query->departamento->departamento;
            return $data;
        });      
        return response()->json($citys);
    }
    /**
     * Obtener las sectores registrados
     *     
     */
    public function getSectores(Request $request)
    {
        $sec=new Sector();
        return response()->json($sec->all());
    }
    /**
     * Obtener los subsectores.
     *     
     */
    public function getSubsectores(Request $request)
    {
       $subsec=new SubSector();
       $subsector= $subsec->where('sector_id', $request->id)->get();      
       return response()->json($subsector);
    }
}
