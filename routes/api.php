<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BusinessUserController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\GearController;
use App\Http\Controllers\m;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('generateOTP', [AuthController::class, 'generateOTP']);
Route::post('verifyCode', [AuthController::class, 'verifyCode']);
Route::post('loginUsingEmail', [AuthController::class, 'loginUsingEmail']);


Route::post('addGear', [GearController::class, 'addGear']);
Route::get('getGears', [GearController::class, 'getGears']);


Route::post('addColor', [ColorController::class, 'addColor']);
Route::get('getColors', [ColorController::class, 'getColors']);

Route::post('addModel', [ModelController::class, 'addModel']);
Route::get('getModels', [ModelController::class, 'getModels']);


Route::post('addBrand', [BrandController::class, 'addBrand']);
Route::get('getBrands', [BrandController::class, 'getBrands']);


Route::post('addBusinessUserProfileInfo', [BusinessUserController::class, 'addBusinessUserProfileInfo']);
Route::post('uploadUserIdImage', [UserController::class, 'uploadUserIdImage']);


Route::post('addCar', [CarController::class, 'addCar']);
Route::post('getCarsByBusinessUserId', [CarController::class, 'getCarsByBusinessUserId']);
