<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('users')->group(function(){

	Route::post('/login',[UsersController::class,'login']);
	Route::post('/recuperarPass',[UsersController::class,'recuperarPass']);

});


Route::middleware(['apitoken','permisos'])->prefix('users')->group(function(){

    Route::put('/registrarEmpleado',[UsersController::class,'registrarEmpleado']);
	Route::get('/listar',[UsersController::class,'listar']);
	Route::get('/detalleEmpleado',[UsersController::class,'detalleEmpleado']);

});


