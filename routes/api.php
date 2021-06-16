<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServicesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Servicios
Route::get('servicios', [ ServicesController::class,'index']);
Route::post('servicios', [ ServicesController::class,'store']);
Route::get('servicios/{servicio}', [ ServicesController::class,'show']);
Route::post('servicios/{id}', [ ServicesController::class,'update']);


//Auth Login
Route::post('register', [ AuthController::class,'register']);
Route::post('login', [ AuthController::class,'login']);
//Route::post('userInfo', [ AuthController::class,'userInfo'])->middleware('auth:sanctum');
//Route::post('logout', [ AuthController::class,'logout'])->middleware('auth:sanctum');

Route::group(['middleware' => ['auth:sanctum']] , function () {
    Route::post('userInfo', [ AuthController::class,'userInfo']);
    Route::post('logout', [ AuthController::class,'logout']);
});