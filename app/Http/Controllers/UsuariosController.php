<?php

namespace App\Http\Controllers;

use App\Models\Ciudades;
use App\Models\DatosBasicos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Experiencia;
use App\Models\Estudio;
use Carbon\Carbon;

class UsuariosController extends Controller
{
    /**
     * Show the application dashboard for users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $datos = array();
        $datos['nombres'] = auth()->user()->nombres;
        $datos['apellidos'] = auth()->user()->apellidos;
        $datos['email'] = auth()->user()->email;
        if (auth()->user()->datosBasicos) {
            $datos['logo'] = auth()->user()->datosBasicos->ruta_avatar;
        }
        $datos = json_encode($datos);
        return view('usuarios.dasboard', compact('datos'));
    }
    /**
     * Show the application dashboard for users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show()
    {
        auth()->user()->load('datosBasicos', 'experiencia', 'estudios', 'perfil');
        return response()->json(auth()->user());
    }  


    /**
     * edit the application dashboard for users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombres' => 'string|required|max:255',
            'apellidos' => 'string|required|max:255',
            'datos_basicos.telefono' => 'integer',
            'datos_basicos.celular'  => 'integer|required',
            'datos_basicos.documento'  => 'integer|required',
            'datos_basicos.fecha_nacimiento'  => 'string|required',
            'datos_basicos.genero'  => 'string|required|max:2',
            'datos_basicos.estado_civil'  => 'string|required|max:20',
            'datos_basicos.departamento'  => 'integer|required',
            'datos_basicos.ciudad'  => 'integer|required',
            'datos_basicos.direccion'  => 'string|required|max:255',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $respuesta['tipo'] = 0;
            $respuesta['mensaje'] = $errors->all();
            return response()->json($respuesta);
        }
        $request->datos_basicos = (object) $request->datos_basicos;
        $user = User::where('id', auth()->user()->id)->with('datosBasicos')->first();
        $user->nombres = $request->nombres;
        $user->apellidos = $request->apellidos;
        $user->update();
        if ($user->datosBasicos == null) {
            $user->datosBasicos = new DatosBasicos();
        }
        $user->datosBasicos->telefono = $request->datos_basicos->telefono;
        $user->datosBasicos->celular = $request->datos_basicos->celular;
        $user->datosBasicos->documento = $request->datos_basicos->documento;
        $user->datosBasicos->fecha_nacimiento = $request->datos_basicos->fecha_nacimiento;
        $user->datosBasicos->genero = $request->datos_basicos->genero;
        $user->datosBasicos->estado_civil = $request->datos_basicos->estado_civil;
        $user->datosBasicos->departamento = $request->datos_basicos->departamento;
        $user->datosBasicos->ciudad = $request->datos_basicos->ciudad;
        $user->datosBasicos->direccion = $request->datos_basicos->direccion;

        if ($user->datosBasicos->user_id != null) {
            $user->datosBasicos->update();
        } else {
            $user->datosBasicos->user_id = $user->id;
            $user->datosBasicos->save();
        }
        $respuesta['tipo'] = 1;
        $respuesta['mensaje'] = 'Se actualizaron los datos correctamente';
        return response()->json($respuesta);
    }
    /**
     * edit the application dashboard for users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updateAvatar(User $user, Request $request)
    {
        $messages = [
            'dimensions' => 'Las dimensiones de la imagen deben ser maximo 700px de ancho por 700px de alto.',
            'image' => 'El archivo es incorrecto',
            'max' => 'El archivo no debe pesar mas de 700kb',
        ];
        $validator = Validator::make($request->all(), [
            'image' => 'image|max:700|dimensions:max_width=700,max_height=700'
        ], $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $respuesta['tipo'] = 0;
            $errores = '';
            foreach ($errors->all() as $key => $value) {
                $errores .= $value . '<br>';
            }
            $respuesta['mensaje'] = $errores;
            return response()->json($respuesta);
        } else {
            
            $user = $user->where('id', auth()->user()->id)->first()->datosBasicos;
            
            if ($user) {
                $directory='usuarios/' . $user->id;                             
                $files = Storage::disk('public')->deleteDirectory($directory);                
                $path = $request->file('image')->store(
                    'usuarios/' . $user->id,
                    'public'
                );
                $user->ruta_avatar            = $path;
                $user->save();
                $respuesta['tipo'] = 1;
                $respuesta['mensaje'] = $user->ruta_avatar;
                return response()->json($respuesta);
            } else {
                $respuesta['tipo'] = 0;
                $respuesta['mensaje'] = 'Antes de cambiar tu foto de perfil primero debes guardar tus datos';
                return response()->json($respuesta);
            }
        }
    }


    // EXPERIENCIAS

    /**
     * Mostrar las experiencias del usuario.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function experience()
    {
        $experiencia = auth()->user()->experiencia->map(function ($query) {
            if ($query->ciudad) {
                $ciudades = Ciudades::find($query->ciudad);
                $query->ciudad = $ciudades->ciudad;
                $query->departamento = $ciudades->departamento->departamento;
            }
            $query->sector = $query->sectorselected->sector;
            $query->subsector = $query->subsectorselected->subsector;
            return $query;
        });
        return response()->json($experiencia);
    }

    /**
     * Guardar la experiencia laboral
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function saveExpe(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'empresa' => 'string|required|max:255',
            'sector' => 'integer|required',
            'subsector' => 'integer',
            'fecha_inicio'  => 'date_format:Y-m-d',
            'trabaja'  => 'boolean',
            'cargo'  => 'string|required|max:255',
            'ciudad'  => 'integer|required',
            'telefono'  => 'string|required|max:10',
            'responsabilidades'  => 'string|required|max:2500'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $respuesta['tipo'] = 0;
            $respuesta['mensaje'] = $errors->all();
            return response()->json($respuesta);
        }
        if (Auth::check()) {
            $exp = new Experiencia();
            $data = $request->all();
            $data['user_id'] = auth()->user()->id;

            $data['trabajo_aqui'] = $request->trabaja;
            if ($request->has('id')) {
                if ($request->trabaja == false || $request->trabaja == 0) {
                    $data['fecha_fin'] = $request->fecha_fin;
                } else {
                    $data['fecha_fin'] = null;
                }
                $exp = Experiencia::where('id', $request->id)->where('user_id', auth()->user()->id)->first();
                if ($exp) {
                    $exp->fill($data)->save();
                    $respuesta['tipo'] = 1;
                    $respuesta['mensaje'] = 'Se Actualizo la experiencia correctamente';
                } else {
                    $respuesta['tipo'] = 0;
                    $respuesta['mensaje'] = 'No puedes editar esta experiencia';
                }
            } else {
                if ($request->trabaja == false) {
                    $data['fecha_fin'] = $request->end_end;
                }
                $data['fecha_inicio'] = $data['start_end'];
                $exp = $exp->create($data);
                $respuesta['tipo'] = 1;
                $respuesta['mensaje'] = 'Se creo la experiencia correctamente';
            }
        } else {
            $respuesta['tipo'] = 0;
            $respuesta['mensaje'] = 'Debe estar autenticado';
        }
        return response()->json($respuesta);
    }

     /**
     * Elimina la experiencia
     *
     * @return Json
     */

    public function deleteExp(Request $request)
    {
        $exp = Experiencia::where('id', $request->id)->where('user_id', auth()->user()->id)->first();
        if ($exp) {
            $exp->delete();
            $respuesta['tipo'] = 1;
            $respuesta['mensaje'] = 'Se elimino la experiencia correctamente';
        } else {
            $respuesta['tipo'] = 0;
            $respuesta['mensaje'] = 'Debe estar autenticado';
        }
        return response()->json($respuesta);
    }



    // ESTUDIOS

    /**
     * Mostrar los estudios del usuario.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function estudios()
    {
        $estudios = auth()->user()->estudios->map(function ($query) {
            if ($query->ciudad_id) {
                $ciudades = Ciudades::find($query->ciudad_id);
                $query->ciudad = $ciudades->ciudad;
                $query->departamento = $ciudades->departamento->departamento;
            }            
            return $query;
        });
        return response()->json($estudios);
    }

     /**
     * Guardar la experiencia laboral
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function saveStudy(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'institucion' => 'string|required|max:255',
            'estado' => 'string|required|max:50',
            'ciudad' => 'integer|required',
            'fecha_inicio'  => 'date_format:Y-m-d',
            'fecha_fin'  => 'date_format:Y-m-d',
            'titulo'  => 'string|required|max:255',            
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $respuesta['tipo'] = 0;
            $respuesta['mensaje'] = $errors->all();
            return response()->json($respuesta);
        }
        if (Auth::check()) {
            $exp = new Estudio();
            $data = $request->all();     
            $data['ciudad_id']=$data['ciudad'];      
            $data['user_id'] = auth()->user()->id;
            if ($request->has('id')) {                
                $exp = Estudio::where('id', $request->id)->where('user_id', auth()->user()->id)->first();
                if ($exp) {
                    $exp->fill($data)->save();
                    $respuesta['tipo'] = 1;
                    $respuesta['mensaje'] = 'Se Actualizo el estudio correctamente';
                } else {
                    $respuesta['tipo'] = 0;
                    $respuesta['mensaje'] = 'No puedes editar este estudio';
                }
            } else {               
                $exp = $exp->create($data);
                $respuesta['tipo'] = 1;
                $respuesta['mensaje'] = 'Se creo el estudio correctamente';
            }
        } else {
            $respuesta['tipo'] = 0;
            $respuesta['mensaje'] = 'Debe estar autenticado';
        }
        return response()->json($respuesta);
    }

     /**
     * Elimina el estudio
     *
     * @return Json
     */

    public function deleteStudy(Request $request)
    {
        $exp = Estudio::where('id', $request->id)->where('user_id', auth()->user()->id)->first();
        if ($exp) {
            $exp->delete();
            $respuesta['tipo'] = 1;
            $respuesta['mensaje'] = 'Se elimino el estudio correctamente';
        } else {
            $respuesta['tipo'] = 0;
            $respuesta['mensaje'] = 'Debe estar autenticado';
        }
        return response()->json($respuesta);
    }


     /**
     * guarda el perfil
     *
     * @return Json
     */

    public function savePerfil(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profesion' => 'string|required|max:255',
            'descripcion' => 'string|required|max:5000',
            'experiencia' => 'integer|required',
            'movilidad'  => 'boolean|required',
            'aspiracion'  => 'integer|required',            
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $respuesta['tipo'] = 0;
            $respuesta['mensaje'] = $errors->all();
            return response()->json($respuesta);
        }
        $data=[
            'user_id'=>auth()->user()->id,
            'profesion'=>$request->profesion,
            'descripcion' => $request->descripcion,
            'experiencia' =>$request->experiencia,
            'aspiracion' =>$request->aspiracion,
            'movilidad' =>$request->movilidad
        ];
        $user=User::find(auth()->user()->id);        
        if($user->perfil){
            $user->perfil->update($data);
            $respuesta['tipo'] = 1;
            $respuesta['mensaje'] = 'Se actualizo la información del perfil correctamente';
        }else{
            $user->perfil()->insert($data);
            $respuesta['tipo'] = 1;
            $respuesta['mensaje'] = 'Se guardo la información del perfil correctamente';
        }        
        return response()->json($respuesta);
    }

}
