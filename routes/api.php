<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotasController;
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
Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::any('/unauthorized', 'unauthorized')->name('unauthorized');
    // Route::post('/logout', 'logout')->name('logout');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::controller(NotasController::class)->group(function () {
        Route::get('/notas', 'getAll')->middleware('auth:sanctum');
        Route::post('/notas', 'save')->middleware('auth:sanctum');
        Route::get('/notas/{id}', 'getById')->middleware('auth:sanctum');
        Route::put('/notas/{id}', 'update')->middleware('auth:sanctum');
    });
});

// Route::controller(NotasController::class)->group(function () {
//     Route::get('/notas', 'getAll');
//     Route::post('/notas', 'save');
// });
// });
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
