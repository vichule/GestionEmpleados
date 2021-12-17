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

    	if ($password) {
    		if (!preg_match(/[a-z]{6,}/,$password)) {
    			$valido = false;
    		}else{
    			$valido = false;
    		}

    		$email = $req->email;

    		if ($email) {
    			
    			if (!preg_match(/[a-z]@{6,}/,$email)) {
    				$valido = false;
    			}else if (User::where('email',$email)->first()) {
    				$valido = false;
    			}

    		}else{
    			$valido = false;
    		}

    		$validator = validator::make(json_decode($req->getContent(),true), 
    			['Nombre' => 'required|max:55', 
    			 'Email' => 'required|email|unique:App\Models\User,email|max:30',
    			 'Password' => 'required|regex:/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,}',
    			 'Salario' => 'required|numeric',
    			 'PuestoTrabajo' => 'required|in:Direccion,RRHH,Empleado',
    			 'Biografia' => 'required|max:100'

    			]);

    		if ($validator->fails()) {
    			$respuesta["status"] = 0
    			$respuesta["msg"] = $validator->errors();
    			
    		}else{

    			$datos = $req->getContent();
    			$datos = json_decode($datos);

    			$usuario = new User();
    			$usuario->Nombre = $datos->nombre;
		    	$usuario->Email = $datos->email;
		    	$usuario->Password = Hash::make($datos->password);
		    	$usuario->Salario = $datos->salario;
		    	$usuario->PuestoTrabajo = $datos->puesto;
		    	$usuario->Biografia = $datos->biografia;
    		}
    	}
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
