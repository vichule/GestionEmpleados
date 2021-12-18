<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

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

        if($request->user->PuestoTrabajo == 'Direccion' || $request->user->PuestoTrabajo == 'RRHH'){
            return $next($request);

        }else{
            $respuesta["status"] = 0;
            $respuesta["msg"] = "Permiso denegado";
        //Fallo
        }

        return response()->json($request);
    }
}
