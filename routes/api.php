<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
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

Route::prefix('account/')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::POST('store', [UserController::class, 'store']);
});

Route::prefix('user/')->group(function () {
    Route::group(['middleware' => ['authJWT']], function () {
        Route::GET('me',        [AuthController::class, 'me']);
        Route::GET('show',      [UserController::class, 'show']);
        Route::PUT('update',    [UserController::class, 'update']);
        Route::POST('addbook',  [UserController::class, 'addbook']);
        Route::DELETE('delete', [UserController::class, 'delete']);
    });
});

Route::prefix('books/')->group(function () {
    Route::group(['middleware' => ['authAdmJWT']], function () {
        Route::GET('user/index',     [UserController::class, 'index']);
        Route::GET('index',     [BookController::class, 'index']);
        Route::GET('show',      [BookController::class, 'show']);
        Route::POST('store',    [BookController::class, 'store']);
        Route::PUT('update',    [BookController::class, 'update']);
        Route::DELETE('delete', [BookController::class, 'delete']);
    });
});
