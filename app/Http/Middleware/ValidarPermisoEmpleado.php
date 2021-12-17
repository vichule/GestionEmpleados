<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidarPermisoEmpleado
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //Comprobar los permisos

        if($request->usuario->cargo == 'Dir' || $request->usuario->cargo == 'RRHH')
        return $next($request);
    }else{
        
        //Fallo
    }
}
