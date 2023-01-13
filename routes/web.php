<?php

use App\Http\Controllers\NotasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('notas', [NotasController::class, 'index']);
Route::post('perform', [NotasController::class, 'perform'])->name('perform');
Route::post('export', [NotasController::class, 'export'])->name('export');
// Route::get('notas/list', [NotasController::class, 'getAll']);
// Route::post('export', [NotasController::class, 'exportToExcel'])->name('export');
