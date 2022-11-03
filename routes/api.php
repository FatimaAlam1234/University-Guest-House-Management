<?php

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

Route::get('/guest', 'API\GuestController@all');
Route::get('/room', 'API\RoomController@all');
Route::get('/room/type', 'API\RoomTypeController@all');
Route::get('/room/services', 'API\RoomServiceController@all');
Route::get('/reservation/checkIns','API\ReservationController@getCheckInGuestsList');
Route::post('/reservation/check-in/{id}','API\ReservationController@checkInGuest');
Route::post('/reservation/check-out/{id}','API\ReservationController@checkOutGuest');
Route::post('/reservation/addService','API\ReservationServiceController@addService');
Route::post('/reservation/bill/{id}','API\BillController@generateBill');
Route::get('/guestHouseInfo','API\DashboardController@getguestHouseInfo');

Route::apiResources([
    'users' => 'API\UserController',
    'guests' => 'API\GuestController',
    'rooms' => 'API\RoomController',
    'room/types' => 'API\RoomTypeController',
    'room/services' => 'API\RoomServiceController',
    'reservations' => 'API\ReservationController',
]);