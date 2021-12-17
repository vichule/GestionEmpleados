<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function registrarEmpleado(Request $req){

    	$password = $req->password;

    	$valido = true;
    }

    public function login(Request $req){
    	//Buscar email
    	$email = $require->email;

    	//validar

    	//encontrar al usuario con ese email
    	$usuario = User::where('email',$email)->first();

    	//Pasar validacion

    	//comprobar contraseña
    	if (Hash::check($req->password, $usuario->password)) {
    		//Todo correcto

    		//Generar el api token
    		do{
    			$token = Hash::make($usuario->id.now());
    		}while (User::where('api_token', $token)->first()); 
    			
    			$usuario->api_token = $token;
    			$usuario->save();

    		return response()->json(.../*Incluir el api token*/);
    		

    	}else{
    		//Login mal

    	}
    }

    public function recuperarPass(Request $req){

    	//Obtener el email y validarlo como login

    	//Buscar email
    	$email = $require->email;

    	//validar

    	//encontrar al usuario con ese email
    	$usuario = User::where('email',$email)->first();

    	
    	//Si encontramos al usuario
    	$usuario->api_token = null;

    	$password = /*generarla aleatoriamente*/;

    	$usuario->password = Hash::make($password);

    	//Enviarla por email

    	//Temporarl: devolver la nueva contraseña en la respuesta
    }

    
}
