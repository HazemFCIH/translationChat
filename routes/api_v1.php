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
Route::post('/register', AuthController::class.'@register');
Route::post('/check_mobile', UserController::class.'@checkUser');
Route::post('/login', AuthController::class.'@login');
Route::get('/all-users', UserController::class.'@allUser');
Route::middleware(['auth:sanctum'])->group(function () {

        Route::post('/logout', AuthController::class.'@logout');
        Route::post('/translate', TranslateController::class.'@translateMessage');
        Route::apiResources(['contact-us' => ContactUsController::class],['only' => ['index','store','destroy','show']]);
        Route::apiResources(['favorites' => FavoritController::class],['only' => ['index','store']]);
        Route::post('delete-favorite',FavoritController::class.'@destroy');
        Route::apiResources(['chats' => ChatController::class]);
        Route::post('update-last-message',ChatController::class.'@updateLastMessage');
        Route::post('contacts',UserController::class.'@contacts');
        Route::post('actives',UserController::class.'@actives');
        Route::get('online',UserController::class.'@online');
        Route::get('offline',UserController::class.'@offline');
        Route::get('/profile', UserController::class.'@userProfile');
        Route::post('/update-profile', UserController::class.'@updateProfile');
        Route::post('/update-number', UserController::class.'@updateNumber');
        Route::post('/delete-user', UserController::class.'@destroy');

});
