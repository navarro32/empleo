<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Empresas
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)    {
        if (Auth::check()) {
            if(auth()->user()->tipo_user==2 && auth()->user()->estado==1){
                return $next($request);
            }else{
                return redirect('/');
            } 
        }else{
            return redirect('/'); 
        }
        
    }
}
