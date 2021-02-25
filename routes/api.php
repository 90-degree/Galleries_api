<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleriesController;
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

Route::get('galleries', [GalleriesController::class, 'index']);
Route::get('galleries/{id}', [GalleriesController::class, 'show']);

Route::middleware(['guest'])->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});
Route::middleware(['auth'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('galleries', [GalleriesController::class, 'store']);
    Route::put('galleries/{id}', [GalleriesController::class, 'update']);
    Route::delete('galleries/{id}', [GalleriesController::class, 'destroy']);
});