<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('sensors', [DashboardController::class, 'sensors'])
    ->middleware(['auth'])
    ->name('sensors');

Route::get('sensors/{nodeId}', [DashboardController::class, 'viewSensor'])
    ->middleware(['auth'])
    ->name('sensor.view');

require __DIR__.'/auth.php';
