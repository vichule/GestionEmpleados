<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function registrarEmpleado(Request $req){
    	$respuesta = ["status" => 1, "msg" => ""];


    		$validator = validator::make(json_decode($req->getContent(),true), 
    			['Nombre' => 'required|max:55', 
    			 'Email' => 'required|email|unique:App\Models\User,email|max:30',
    			 'Password' => 'required|regex:/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,}/',
    			 'Salario' => 'required|numeric',
    			 'PuestoTrabajo' => 'required|in:Direccion,RRHH,Empleado',
    			 'Biografia' => 'required|max:100'

    			]);

    		if ($validator->fails()) {
    			$respuesta["status"] = 0;
    			$respuesta["msg"] = $validator->errors();
    			
    		}else{

    			$datos = $req->getContent();
    			$datos = json_decode($datos);

    			$usuario = new User();
    			$usuario->Nombre = $datos->Nombre;
		    	$usuario->Email = $datos->Email;
		    	$usuario->Password = Hash::make($datos->Password);
		    	$usuario->Salario = $datos->Salario;
		    	$usuario->PuestoTrabajo = $datos->PuestoTrabajo;
		    	$usuario->Biografia = $datos->Biografia;

		    	try{
		            
		    		$usuario->save();
		    		$respuesta['msg'] = "Usuario guardado con id ".$usuario->id;
		            
		    	}catch(\Exception $e){
		    		$respuesta['status'] = 0;
		    		$respuesta['msg'] = "Se ha producido un error ".$e->getMessage();
		    	}
		    	
    		}
    		return response()->json($respuesta);
    }


    public function login(Request $req){
		$respuesta = ["status" => 1, "msg" => ""];
    	//Buscar email
    	$email = $require->email;

		//validar
    	//encontrar al usuario con ese email
    	$usuario = User::where('Email',$email)->first();

    	//Pasar validacion

    	//comprobar contraseña
    	if (Hash::check($req->password, $usuario->password)) {
    		//Todo correcto

    		//Generar el api token
    		do{
    			$apitoken = Hash::make($usuario->id.now());
    		}while (User::where('api_token', $apitoken)->first()); 
    			
    			$usuario->api_token = $apitoken;
    			$usuario->save();

				try{
		            
		    		return response()->json($apitoken);
		            
		    	}catch(\Exception $e){
		    		$respuesta['status'] = 0;
		    		$respuesta['msg'] = "Se ha producido un error ".$e->getMessage();
		    	}

    	}else{
    		//Login mal
			$respuesta["status"] = 0;
            $respuesta["msg"] = "El login ha fallado, pruebe de nuevo";
    	}
    }

    // public function recuperarPass(Request $req){

    // 	//Obtener el email y validarlo como login

    // 	//Buscar email
    // 	$email = $require->email;

    // 	//validar

    // 	//encontrar al usuario con ese email
    // 	$usuario = User::where('email',$email)->first();

    	
    // 	//Si encontramos al usuario
    // 	$usuario->api_token = null;

    // 	$password = /*generarla aleatoriamente*/;

    // 	$usuario->password = Hash::make($password);

    // 	//Enviarla por email

    // 	//Temporarl: devolver la nueva contraseña en la respuesta
    // }


}
