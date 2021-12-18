<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class Apitoken
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
            if($user = User::where('api_token',$apitoken)->first()){
                $user = User::where('api_token',$apitoken)->first();
                $respuesta["msg"] = "Api token valido";
                $request->user = $user;
                return $next($request);
            }else{
                $respuesta["msg"] = "Token invalido";
            }

        }else{
            $respuesta["status"] = 0;
            $respuesta["msg"] = "Token no ingresado";
        }

        return response()->json($respuesta);
    }
}