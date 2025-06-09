<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BusinessUserController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoritesControlle;
use App\Http\Controllers\GearController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\m;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationsController;
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
Route::post('getBusinessUser', [BusinessUserController::class, 'getBusinessUser']);
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
Route::post('getCarDetails', [CarController::class, 'getCarDetails']);
Route::post('toggleCarAvailability', [CarController::class, 'toggleCarAvailability']);
Route::get('getAllCars', [CarController::class, 'getAllCars']);



//account
Route::post('addBalance', [AccountController::class, 'addBalance']);
Route::post('deductBalance', [AccountController::class, 'deductBalance']);
Route::post('getAccount', [AccountController::class, 'getAccount']);


//order
Route::post('addOrder', [OrderController::class, 'addOrder']);
Route::post('getOrderByCompanyId', [OrderController::class, 'getOrderByCompanyId']);
Route::post('changeOrderStatus', [OrderController::class, 'changeOrderStatus']);
Route::post('getOrderByUserId', [OrderController::class, 'getOrderByUserId']);
Route::post('getOrderToDelivery', [OrderController::class, 'getOrderToDelivery']);
Route::get('getAllOrders', [OrderController::class, 'getAllOrders']);
Route::post('getOrdegetOrderByCompanyIdrToDelivery', [OrderController::class, 'getOrderByCompanyId  ']);


//rate

Route::post('rateCar', [RateController::class, 'rateCar']);


//comment

Route::post('addComment', [CommentController::class, 'addComment']);


//likes
Route::post('addLike', [LikesController::class, 'addLike']);

//favorite

Route::post('addFavorite', [FavoritesControlle::class, 'adsFavorite']);
Route::post('getUserFavorites', [FavoritesControlle::class, 'getUserFavorites']);


//report
Route::post('addReport', [ReportController::class, 'addReport']);
Route::post('getCarReports', [ReportController::class, 'getCarReports']);

//reservations
Route::get('getAllReservations', [ReservationsController::class, 'getAllReservations']);

Route::post('addReservation', [ReservationsController::class, 'addReservation']);
Route::get('/user/{userId}/reservations', [ReservationsController::class, 'getUserReservations']);
Route::post('getBusinessUserReservations', [ReservationsController::class, 'getBusinessUserReservations']);

Route::post('statistics', [App\Http\Controllers\StatisticsController::class, 'getStatistics']);
