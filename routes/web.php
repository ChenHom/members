<?php

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware('auth')->group(function () {

    Route::get('users', [UserController::class, 'list'])->name('user.list')->can('user.viewAny');
    Route::patch('user/{user}', [UserController::class, 'update'])->name('user.update')->can('user.update');

    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->can('user.view');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update')->can('user.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy')->can('user.delete');
});


require __DIR__ . '/auth.php';
