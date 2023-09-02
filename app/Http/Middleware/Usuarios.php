<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Oferta;
use App\Http\Controllers\AplicacionController as Aplicacion;
use Symfony\Component\HttpFoundation\Response;
class Usuarios
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (auth()->user()->tipo_user == 3 && auth()->user()->estado==1) {
                $url =url()->previous();
                
                $url_components = parse_url($url);  
                if(isset($url_components['query'])){
                    parse_str($url_components['query'], $params);
                    if(isset($params['id'])){
                        $oferta = Oferta::where('uuid', $params['id'])->first();                                       
                        if($oferta){
                            $response=Aplicacion::insert(Auth::user(), $oferta);                       
                        }        
                        return redirect('/oferta/'.$oferta->slug.'?uuid='.$params['id']);
                    }
                } 
                
                return $next($request);
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
        
}
}

