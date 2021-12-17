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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('users')->group(function(){

Route::put('/registrarEmpleado',[UsersController::class,'registrarEmpleado']);
Route::post('/login/{Email}/{Password}',[UsersController::class,'login']);
Route::get('/recuperarPass{Email}',[UsersController::class,'recuperarPass']);
Route::get('/listar',[UsersController::class,'listar']);

});
//Route::middleware('apitoken')->get('/protegido-sin-permiso',....)

//Route::middleware(['apitoken','permisos'])->get('/protegido-con-permiso',....)