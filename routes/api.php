<?php

use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\UserController;
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

Route::post('login', [LoginController::class, 'login']);

Route::middleware(['api.token.auth'])->prefix('chat')->group(function () {
    Route::post('send', [ChatController::class, 'send']);
    Route::post('messages', [ChatController::class, 'getMessages']);

    Route::post('create', [ChatController::class, 'create']);
    Route::post('chats', [ChatController::class, 'getChats']);
});

Route::middleware(['api.token.auth'])->prefix('user')->group(function () {
    Route::post('check', [UserController::class, 'check']);
});
