<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', 'Auth\LoginController@index')->name('login');
Route::post('/login', 'Auth\LoginController@login');

Route::get('/register', 'Auth\RegisterController@index')->name('register');
Route::post('/register', 'Auth\RegisterController@register');

Route::post('/logout', 'Auth\LogoutController@logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');
Route::get('/users', 'Admin\DashboardController@index')->name('users');
Route::get('/guests', 'Admin\DashboardController@index')->name('guests');
Route::get('/rooms', 'Admin\DashboardController@index')->name('rooms');
Route::get('/rooms/{id}', 'Admin\DashboardController@index')->name('rooms.detail');
Route::get('/room/types', 'Admin\DashboardController@index')->name('room.types');
Route::get('/room/services', 'Admin\DashboardController@index')->name('room.services');
Route::get('/reservations', 'Admin\DashboardController@index')->name('reservations');
Route::get('/reservation/checkIns', 'Admin\DashboardController@index')->name('check-Ins');
Route::get('/profile/{id}', 'Admin\DashboardController@index')->name('profile');
Route::get('/bill/{id}', 'Admin\DashboardController@index')->name('generate.bill');


/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::get('/', 'User\HomeController@index')->name('user.home');
Route::get('/our-rooms', 'User\HomeController@rooms')->name('user.rooms');
Route::post('/check-rooms', 'User\HomeController@checkRooms')->name('user.checkRooms');
Route::get('/room/{id}/{checkIn}/{checkOut}', 'User\HomeController@room')->name('user.room');
Route::post('/room/{id}', 'User\HomeController@bookRoom')->name('user.bookRoom');
Route::get('/about-us', 'User\HomeController@aboutUs')->name('user.rooms');
Route::get('/contact-us', 'User\HomeController@contactUs')->name('user.rooms');