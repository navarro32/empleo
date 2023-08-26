<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use Illuminate\Http\Request;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AplicacionController as Aplicacion;
class EmpleosController extends Controller
{
    
    private $nicenames = [
        'campo'                 => 'Campo de busquedad',
        'depar'                => 'Departamento',
        'filtros'               => 'Filtros'
    ];
    public function index()
    {
        return view('searchs.all');
    }
    public function Buscar(Request $request)
    {
        $this->validate($request, [
            'campo'                 => 'string|nullable',
            'depar'                => is_array($request->depar) ? 'required|array' : 'required|string|max:3',
            'filtros' => 'required|array',
            'tipo' => 'required|string|max:1'
        ], [], $this->nicenames);
        $keywords = Purify::clean($request->campo);


        if ($request->tipo == '1') {
            $ofertas = Oferta::where(function ($query) use ($keywords) {
                return $query->where('titulo', 'like', '%' . $keywords . '%')
                    ->orWhere('descripcion', 'like', '%' . $keywords . '%');
            });

            $aKeyword = explode(' ', $keywords);
            for ($i = 1; $i < count($aKeyword); $i++) {
                if (!empty($aKeyword[$i])) {
                    $ofertas->where(function ($query) use ($aKeyword, $i) {
                        return $query->where('titulo', 'like', '%' . $aKeyword[$i] . '%');
                    });
                }
            }
        } else if ($request->tipo == '2') {
            $ofertas = Oferta::where(function ($query) use ($keywords) {
                return $query->where('titulo', 'like', $keywords)
                    ->orWhere('descripcion', 'like',  $keywords);
            });
        } else {
            return $this->showMessage('Es requerido el campo de tipo de busquedad', Response::HTTP_CONFLICT);
        }
        // filtro por departamento
        if ($request->depar) {
            if ($request->depar != 'all') {
                $departs = collect($request->depar)->pluck('id');
                $ofertas = $ofertas->whereHas('ciudad.departamento', function ($query) use ($departs) {
                    return $query->whereIn('departamento.id',  $departs);
                });
            }
        }
        // demas filtros

        if ($request->filtros) {
            foreach ($request->filtros as $key => $value) {
                if ($value['field'] == 'contrato' && !is_null($value['campo'])) {
                    // filtro por contrato
                    $ofertas = $ofertas->whereIn($value['field'], $value['campo']);
                } elseif ($value['field'] == 'experiencia' && !is_null($value['campo'])) {
                    // filtro por experiencia
                    $filtros = collect($value['campos'])->whereIn('id', $value['campo'])->pluck('bd');
                    if ($filtros->count() > 0) {
                        $ofertas = $ofertas->where(function ($query) use ($filtros) {
                            foreach ($filtros as $key2 => $value2) {
                                $query->orwhereBetween('experiencia', $value2);
                            }
                        });
                    }
                } elseif ($value['field'] == 'sueldo' && !is_null($value['campo'])) {
                    $filtros = collect($value['campos'])->whereIn('id', $value['campo'])->pluck('bd');
                    // dd($filtros->toArray());
                    $ofertas = $ofertas->whereBetween('sueldo', $filtros->toArray());
                }
            }
        }

        $ofertas = $ofertas->with('ciudad')->get();
        return $this->showAll($ofertas);
    }

    public function show(Request $request)
    {
        $url_back = $request->headers->get('referer');        
        $oferta = Oferta::where('uuid', $request->uuid)->with('ciudad.departamento', 'area', 'user.empresas')->first();
        if ($oferta) {
            $aplico = AplicacionController::getAplicado($oferta);
            return view('show', compact('oferta', 'url_back', 'aplico'));
        } else {
            abort(404);
        }
    }
    public function Aplicar(Request $request)
    {
        $oferta = Oferta::where('uuid', $request->id)->first();
        if ($oferta) {
            if (Auth::check()) {
                $user = Auth::user();
                $response=Aplicacion::insert($user, $oferta);
                if($response){
                    return $this->showMessage('Aplico correctamente', Response::HTTP_OK);                    
                }else{
                    return $this->showMessage('Aplicado anteriormente', Response::HTTP_CONFLICT);                     
                }
            }else{
                return $this->showMessage('Debes ingresar para registrar la aplicación', Response::HTTP_UNAUTHORIZED);
            }
        }else{
            return $this->showMessage('Oferta no encontrada', Response::HTTP_FORBIDDEN);
        }
    }
}
