<?php

use App\Http\Controllers\ItemAutoBidController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

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
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:api')->group( function () {
    Route::get('logout', [LoginController::class, 'logout']);

    #USER
    Route::get('/user', [UserController::class, 'index']);
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{user}', [UserController::class, 'destroy']);
    Route::get('/me', [UserController::class, 'show']);
    Route::put('/me', [UserController::class, 'updateMe']);

    #ITEM
    Route::get('item', [ItemController::class, 'index']);
    Route::post('item', [ItemController::class, 'store']);
    Route::get('item/{item}', [ItemController::class, 'show']);
    Route::put('item/{item}', [ItemController::class, 'update']);
    Route::post('item/{item}/bid', [ItemController::class, 'bid']);
    Route::get('item/{item}/subscribe', [ItemAutoBidController::class, 'subscribe']);
    Route::get('item/{item}/unsubscribe', [ItemAutoBidController::class, 'unsubscribe']);
});
