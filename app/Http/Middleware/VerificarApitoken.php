<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
        $apitoken = $req->api_token;


        $usuario = User:where('api_token',$apitoken)->first();

        if (!$usuario) {
            //Fallo


        }else{
            $request->usuario = $usuario;
            return $next($request);
        }
        
    }
}
