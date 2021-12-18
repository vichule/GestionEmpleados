<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class VerificarApitoken
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
        //Buscar al usuario
        if(isset($request->api_token)){

            $apitoken = $request->api_token;
            if($usuario = User::where('api_token',$apitoken)->first()){
                $usuario = User::where('api_token',$apitoken)->first();
                $respuesta["msg"] = "El Api token es correcto";
                $request->user = $usuario;
                return $next($request);
            }else{
                $respuesta["msg"] = "Api Token incorrecto";
            }

        }else{
            $respuesta["status"] = 0;
            $respuesta["msg"] = "Api Token no ha sido introducido";
        }

        return response()->json($respuesta);
    }
}
