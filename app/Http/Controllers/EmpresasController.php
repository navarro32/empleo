<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Oferta;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class EmpresasController extends Controller
{
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('Empresas');
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nombres' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],            
            'razon_social' => ['required', 'string', 'max:255'],
            'nit' => ['required', 'integer'],
            'direccion' => ['required', 'string', 'max:255'],
            'telefono' => ['integer'],
            'url' => ['required', 'string', 'max:255'],
            'logo' => ['image', 'max:700'],
            'descripcion' => ['string'],
        ]);
    }

    protected function tokensMatch($request) { // If request is an ajax request, then check to see if token matches token provider in // the header. This way, we can use CSRF protection in ajax requests also. 
        $token = $request->ajax() ? $request->header('X-CSRF-Token') : $request->input('_token'); return $request->session()->token() == $token; 
    }
    protected function validatorOfertas(array $data)
    {
        return Validator::make($data, [
            'nombres' => ['required', 'string', 'max:255'],
            'sueldo' => ['required',  'max:255'],
            'area' => ['required', 'integer'],            
            'departamento' => ['required', 'integer'],
            'ciudades' => ['required', 'integer'],
            'vacantes' => ['required', 'integer'],
            'experiencia' => ['required', 'integer'],
            'tipoFecha' => ['required'],
            'contrato' => ['required'],
            'descripcion' => ['string'],
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $datos = array();
        $datos['nombres'] = auth()->user()->nombres;
        $datos['apellidos'] = auth()->user()->apellidos;
        $datos['email'] = auth()->user()->email;
        $datos['razon_social'] = auth()->user()->empresas->razon_social; 
        $datos['nit'] = auth()->user()->empresas->nit;  
        $datos['direccion'] = auth()->user()->empresas->direccion; 
        $datos['telefono'] = auth()->user()->empresas->telefono; 
        $datos['url'] = auth()->user()->empresas->url; 
        $datos['logo'] = filter_var(auth()->user()->empresas->logo, FILTER_VALIDATE_URL)?auth()->user()->empresas->logo: asset(Storage::url(auth()->user()->empresas->logo)); 
        $datos['descripcion'] = auth()->user()->empresas->descripcion;    
        $datos=json_encode($datos);     
        return view('empresas.dasboard', compact('datos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEmpresa()
    {
        $datos = array();
        $datos['nombres'] = auth()->user()->nombres;
        $datos['apellidos'] = auth()->user()->apellidos;
        $datos['email'] = auth()->user()->email;
        $datos['razon_social'] = auth()->user()->empresas->razon_social; 
        $datos['nit'] = auth()->user()->empresas->nit;  
        $datos['direccion'] = auth()->user()->empresas->direccion; 
        $datos['telefono'] = auth()->user()->empresas->telefono; 
        $datos['url'] = auth()->user()->empresas->url;         
        $datos['descripcion'] = auth()->user()->empresas->descripcion; 
        return response()->json($datos);
    }    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveOfert(Request $request, Oferta $oferta)
    {
        $validator=$this->validatorOfertas($request->toArray());
        $respuesta=array();
        if ($validator->fails()) {
            $errors=$validator->errors();
            foreach ($errors->all() as $message) {
                $respuesta['mensaje'][]=$message;            
            }
            $respuesta['tipo']=0;
        }else{
            $oferta->titulo=$request->nombres;
            $oferta->sueldo=$request->sueldo;
            $oferta->area_id=$request->area;
            $oferta->ciudad_id=$request->ciudades;
            $oferta->vacantes=$request->vacantes;
            $oferta->descripcion=$request->descripcion;
            $oferta->contrato=$request->contrato;
            $oferta->estado=1;
            $oferta->user_id=auth()->user()->id;
            $oferta->uuid=(string) Str::uuid();
            $oferta->experiencia=($request->tipoFecha==2)?$request->experiencia*12:$request->experiencia;
            $oferta->save();
            $respuesta['tipo']=1;
            $respuesta['mensaje']="Se han creado la oferta";
        }
        return response()->json($respuesta);
    }

    /**
     * Editar la oferta
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editOfert(Request $request, Oferta $oferta)
    {
        $validator=$this->validatorOfertas($request->toArray());
        $respuesta=array();
        if ($validator->fails()) {
            $errors=$validator->errors();
            foreach ($errors->all() as $message) {
                $respuesta['mensaje'][]=$message;            
            }
            $respuesta['tipo']=0;
        }else{
            $oferta=$oferta->where('uuid', $request->id)->first();
            $oferta->titulo=$request->nombres;
            $oferta->sueldo=$request->sueldo;
            $oferta->area_id=$request->area;
            $oferta->ciudad_id=$request->ciudades;
            $oferta->vacantes=$request->vacantes;
            $oferta->descripcion=$request->descripcion;
            $oferta->contrato=$request->contrato;
            $oferta->estado=1;
            $oferta->user_id=auth()->user()->id;
            $oferta->experiencia=($request->tipoFecha==2)?$request->experiencia*12:$request->experiencia;
            $oferta->update();
            $respuesta['tipo']=1;
            $respuesta['mensaje']="Se ha editado la oferta";
        }
        return response()->json($respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function show(Empresa $empresa)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function edit(Empresa $empresa)
    {
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Empresa $empresa)
    {
        $validator=$this->validator($request->toArray());
        $respuesta=array();
        if ($validator->fails()) {
            $errors=$validator->errors();
            foreach ($errors->all() as $message) {
                $respuesta['mensaje'][]=$message;            
            }
            $respuesta['tipo']=0;
        }else{
             //  Se actualizan los datos de la tabla Users  
            $datos=User::where('id', auth()->user()->id)
            ->first();
            $datos->nombres=$request->nombres;
            $datos->apellidos=$request->apellidos;
            $datos->save(); 
            $afectadas=$datos->wasChanged();   
            //  Se actualizan los datos de la tabla relacionada  
            $datos2=Empresa::where('user_id', auth()->user()->id)
            ->first();
            $datos2->telefono=$request->telefono;
            $datos2->direccion=$request->direccion;
            $datos2->url=$request->url;
            $datos2->descripcion=$request->descripcion;
            $datos2->save();
            $afectadas2=$datos2->wasChanged();             
           
            if($afectadas || $afectadas2){
                $respuesta['tipo']=1;
                $respuesta['mensaje']="Se han actualizado tus datos";
            }else{
                $respuesta['mensaje'][]='No realizo ninguna modificación'; 
                $respuesta['tipo']=2;
            }
               
        }
        return response()->json($respuesta);
    }

    public function updatePass(Request $request, User $user){
        $respuesta=array();
        $usuario=$user->where('id', auth()->user()->id)->first();
        if(Hash::check($request->passOld, auth()->user()->password)){
            if($request->pass != $request->pass2){
                $respuesta['mensaje'][]='Las contraseñas no coinciden'; 
                $respuesta['tipo']=0;
            }else{
                $usuario->password=Hash::make($request->pass);
                $usuario->save();
                $respuesta['tipo']=1;
                $respuesta['mensaje']="Se Actualizo la contraseña";
            }
        }else{
            $respuesta['mensaje'][]='La contraseña anterior es incorrecta'; 
            $respuesta['tipo']=0;
        }
        return response()->json($respuesta);
    }

     /**
     * Obtiene la lista de ofertas.
     *
     * @param  \App\Models\Oferta  $oferta
     * @return \Illuminate\Http\Response
     */
    public function getOferts(Oferta $oferta){
        $ofertas=$oferta->where('user_id', auth()->user()->id)->get();
        $datos=array();
        
        foreach ($ofertas as $key => $value) {
            $exp=($value->experiencia>1)?$value->experiencia.' meses':$value->experiencia.' mes';            
            $datos[]=[
                'id'=>$value->uuid,
                'titulo'=>$value->titulo,
                'sueldo'=>number_format($value->sueldo, 0, ',', '.'),
                'ciudad'=>$value->ciudad->departamento->departamento.' / '.$value->ciudad->ciudad,
                'vacantes'=>$value->vacantes,
                'experiencia'=>$exp, 
                'estado'=>$value->estado
            ];
           
        }
        return response()->json($datos);
    }

    /**
     * Obtiene la lista de ofertas.
     *
     * @param  \App\Models\Oferta  $oferta
     * @return \Illuminate\Http\Response
     */
    public function getOfert(Oferta $ofertas, Request $request){
        $oferta=$ofertas
        ->where('user_id', auth()->user()->id)
        ->where('uuid', $request->id)
        ->join('ciudad', 'ciudad.id', '=', 'ofertas.ciudad_id')
        ->join('departamento as d', 'd.id', '=', 'ciudad.departamento_id')
        ->select('ofertas.*', 'ciudad.id as ciudad_id', 'd.id as departamento_id') 
        ->first();  
               
        return response()->json($oferta);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Oferta  $oferta
     * @return \Illuminate\Http\Response
     */
    public function deleteOfert($id, Oferta $oferta)
    {
        $oferta->where('uuid', $id)->delete();
        $respuesta['mensaje']='Oferta eliminada'; 
        $respuesta['tipo']=1;
        return response()->json($respuesta);
    }
}
