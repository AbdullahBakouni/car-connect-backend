<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BusinessUserController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\GearController;
use App\Http\Controllers\m;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// auth
Route::post('generateOTP', [AuthController::class, 'generateOTP']);
Route::post('verifyCode', [AuthController::class, 'verifyCode']);
Route::post('loginUsingEmail', [AuthController::class, 'loginUsingEmail']);
Route::get('getUsers', [AuthController::class, 'getUsers']);


//gear
Route::post('addGear', [GearController::class, 'addGear']);
Route::get('getGears', [GearController::class, 'getGears']);


//color
Route::post('addColor', [ColorController::class, 'addColor']);
Route::get('getColors', [ColorController::class, 'getColors']);



//model
Route::post('addModel', [ModelController::class, 'addModel']);
Route::get('getModels', [ModelController::class, 'getModels']);


//brand
Route::post('addBrand', [BrandController::class, 'addBrand']);
Route::get('getBrands', [BrandController::class, 'getBrands']);


//business user
Route::post('addBusinessUserProfileInfo', [BusinessUserController::class, 'addBusinessUserProfileInfo']);
Route::get('getBusinessUsers', [BusinessUserController::class, 'getBusinessUsers']);
Route::get('getCompanyUsers', [BusinessUserController::class, 'getCompanyUsers']);


//user
Route::post('uploadUserIdImage', [UserController::class, 'uploadUserIdImage']);


//car
Route::post('addCar', [CarController::class, 'addCar']);
Route::post('getCarsByBusinessUserId', [CarController::class, 'getCarsByBusinessUserId']);
Route::get('getNewestCars', [CarController::class, 'getNewestCars']);
Route::get('getCars', [CarController::class, 'getCars']);
Route::post('getCarsByUserId', [CarController::class, 'getCarsByUserId']);
Route::post('getCarsByBrandId', [CarController::class, 'getCarsByBrandId']);



//account
Route::post('addBalance', [AccountController::class, 'addBalance']);
Route::post('getUserPayCard', [AccountController::class, 'getUserPayCard']);


//order
Route::post('addOrder', [OrderController::class, 'addOrder']);
Route::post('acceptOrder', [OrderController::class, 'acceptOrder']);
Route::post('getOrderByUserId', [OrderController::class, 'getOrderByUserId']);
Route::post('getOrderToDelivery', [OrderController::class, 'getOrderToDelivery']);


