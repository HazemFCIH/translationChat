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
Route::middleware(['auth:sanctum'])->group(function () {

        Route::post('/logout', AuthController::class.'@logout');
        Route::post('/translate', TranslateController::class.'@translateMessage');
        Route::apiResources(['contact-us' => ContactUsController::class],['only' => ['index','store','delete','show']]);
        Route::apiResources(['chats' => ChatController::class]);
        Route::post('contacts',UserController::class.'@contacts');
            Route::get('/profile', UserController::class.'@userProfile');

});
