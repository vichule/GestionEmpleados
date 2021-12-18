<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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

		$datos = $req->getContent();
		$datos = json_decode($datos);

    	//Buscar email
    	$email = $datos->Email;

		//validar
    	//encontrar al usuario con ese email
    	
		
    	//Pasar validacion
		if($usuario = User::where('Email',$email)->first()){
			$usuario = User::where('Email',$email)->first();
			//comprobar contraseña
			if (Hash::check($datos->Password, $usuario->Password)) {
				//Todo correcto
	
				//Generar el api token
				do{
					$apitoken = Hash::make($usuario->id.now());
				}while (User::where('api_token', $apitoken)->first()); 
					
					$usuario->api_token = $apitoken;
					$usuario->save();
	
					try{
						$respuesta["status"] = 0;
						$respuesta["msg"] = "Login correcto  ".$usuario->api_token;
						//return response()->json($apitoken);
						
					}catch(\Exception $e){
						$respuesta['status'] = 0;
						$respuesta['msg'] = "Se ha producido un error ".$e->getMessage();
					}
	
			}else{
				//Login mal
				
				$respuesta["status"] = 0;
				$respuesta["msg"] = "El login ha fallado, pruebe de nuevo ".$usuario->Nombre;
			}

		}else{
			
				$respuesta["status"] = 0;
				$respuesta["msg"] = "El login ha fallado, pruebe de nuevo";
			
		}
    	return response()->json($respuesta);

		//contraseña RRHH Hola123 -> apitoken 
		//contraseña Javier(directivo) 11uErr4 *nueva(SO2rpa4Z) -> apitoken $2y$10$nnIAIafNQkKdtH2omANtsOtBE4FBThOwr43t44gxDjpa7XRZq3lwi
    }

    public function recuperarPass(Request $req){

		$respuesta = ["status" => 1, "msg" => ""];

		$datos = $req->getContent();
		$datos = json_decode($datos);

    	//Buscar email
    	$email = $datos->Email;
    	//Obtener el email y validarlo como login

		if($usuario = User::where('Email',$email)->first()){
			$usuario = User::where('Email',$email)->first();
			//Si encontramos al usuario 
			$usuario->api_token = null;

			//$newPassword = /*generarla aleatoriamente*/;

			$password = Str::random(8);
			
			$usuario->Password = Hash::make($password);
			$usuario->save();

			try{
				//Enviarla por email
				Mail::to($usuario->Email)->send(new OrderShipped($password));
				$respuesta["status"] = 0;
				$respuesta["msg"] = "Password enviada al email";
				
			}catch(\Exception $e){
				$respuesta['status'] = 0;
				$respuesta['msg'] = "Se ha producido un error ".$e->getMessage();
			}

		}else{
			
				$respuesta["status"] = 0;
				$respuesta["msg"] = "El email es incorrecto o no existe";
			
		}
    	return response()->json($respuesta);	
    }


	public function listar(Request $req){

        $respuesta = ["status" => 1, "msg" => ""];
		$datos = $req-> getContent();
        $datos = json_decode($datos);  
			
		$apitoken = $req->query('api_token');
		$empleados = $req->query('empleados');
			
			try{

				if(User::where('api_token','=',$apitoken)->first()){
					
					$usuario = User::where('api_token','=',$apitoken)->first();
					
					if($usuario->PuestoTrabajo == 'RRHH'){
						
						$empleados = DB::Table('users')
						->select('Nombre', 'PuestoTrabajo', 'Salario')
						->where('PuestoTrabajo', 'like', 'Empleado')
						->get();
					}else if($usuario->PuestoTrabajo == 'Direccion'){
						
						$empleados = DB::Table('users')
						->select('Nombre', 'PuestoTrabajo', 'Salario')
						->where(function($puesto){
							$puesto->where('PuestoTrabajo', 'like', 'Empleado')
							->orWhere('PuestoTrabajo', 'like', 'RRHH');
						})
						
						->get();

					}
					
					$respuesta = $empleados;
				}			
			}catch(\Exception $e){
				$respuesta['status'] = 0;
				$respuesta['msg'] = "Se ha producido un error: ".$e->getMessage();
			}		
		return response()->json($respuesta);
	}


	public function detalleEmpleado(Request $req){

        $respuesta = ["status" => 1, "msg" => ""];
		$datos = $req-> getContent();
        $datos = json_decode($datos);  
			
		$apitoken = $req->query('api_token');
		$empleadoSeleccionadoID = $req->query('empleadoID');
		$empleadoSeleccionado = User::find($empleadoSeleccionadoID);

			try{

				if(User::where('api_token','=',$apitoken)->first()){

					$usuario = User::where('api_token','=',$apitoken)->first();
					if($empleadoSeleccionado){
						
						if($usuario->PuestoTrabajo == 'RRHH'){
						
							$detallesEmpleado = DB::Table('users')
							->select('Nombre','Email', 'Biografia', 'PuestoTrabajo', 'Salario')
							->where('id', '=', $empleadoSeleccionadoID)
							->where('PuestoTrabajo', 'like', 'Empleado')
							->get();
						}else if($usuario->PuestoTrabajo == 'Direccion'){

							$detallesEmpleado = DB::Table('users')
							->select('Nombre','Email', 'Biografia', 'PuestoTrabajo', 'Salario')
							->where('id', '=', $empleadoSeleccionadoID)
							->where(function($puesto){
								$puesto->where('PuestoTrabajo', 'like', 'Empleado')
								->orWhere('PuestoTrabajo', 'like', 'RRHH');
							})
							
							->get();
							
						}
						
						$respuesta = $detallesEmpleado;

					}else{

						$respuesta['status'] = 0;
						$respuesta['msg'] = "El usuario que busca no existe o se ha equivocado";
					}
					
				}			
			}catch(\Exception $e){
				$respuesta['status'] = 0;
				$respuesta['msg'] = "Se ha producido un error: ".$e->getMessage();
			}		
		return response()->json($respuesta);
	}

}
