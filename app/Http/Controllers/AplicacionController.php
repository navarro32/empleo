<?php

namespace App\Http\Controllers;

use App\Models\Aplicacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AplicacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    static public function Insert($user,  $ofert)
    {
        if ($user->tipo_user == 3) {
            $apli = Aplicacion::where('user_id', $user->id)->where('empresa_id', $ofert->user_id)->whereDate('fecha_aplicacion', now()->format('Y-m-d'))->first();
            
            if ($apli) {
                $ofertas = collect($apli->ofertas);
                $fechas = collect($apli->fechas);
                $search = $ofertas->where('id', $ofert->id)->first();
                if (is_null($search)) {
                    $ofertas->push((object)['id' => $ofert->id]);
                    $fechas->push((object)['fecha' => Carbon::now()->format('Y-m-d H:i')]);
                    $apli->ofertas = $ofertas->toArray();
                    $apli->fechas = $fechas->toArray();
                    $apli->fecha_aplicacion = now()->format('Y-m-d');
                    $apli->update();
                    return true;
                } else {
                    return false;
                }
            } else {
                $oferta = [
                    'empresa_id' => $ofert->user_id,
                    'user_id'   => $user->id,
                    'fecha_aplicacion' => now()->format('Y-m-d'),
                    'ofertas'   => json_encode([['id' => $ofert->id]]),
                    'fechas'    => json_encode([['fecha' => Carbon::now()->format('Y-m-d H:i')]]),
                    'created_at' =>   Carbon::now()->format('Y-m-d H:i')
                ];
                
                DB::table('aplicaciones')->insert($oferta);
                return true;
            }
        } else {
            return false;
        }
    }
    static public function getAplicado($ofert){    
        if(!Auth::check()){
            return false;
        }
        $aplicacion = Aplicacion::where('empresa_id', $ofert->user->id)
        ->where('user_id', auth()->user()->id)->first();
        if($aplicacion){
            $ofertas=collect($aplicacion->ofertas);
            $find = $ofertas->where('id', $ofert->id)->count();
            if($find>0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
