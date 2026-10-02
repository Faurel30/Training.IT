<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkoutController;

/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIK
|--------------------------------------------------------------------------
| Bisa diakses tanpa login.
*/

Route::get('/', [PageController::class, 'landing'])
    ->name('landing');

Route::get('/learn-more', [PageController::class, 'about'])
    ->name('about');


/*
|--------------------------------------------------------------------------
| REGISTER
|--------------------------------------------------------------------------
*/

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'processRegister'])
    ->name('register.process');


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'processLogin'])
    ->name('login.process');


/*
|--------------------------------------------------------------------------
| FORGOT DAN RESET PASSWORD
|--------------------------------------------------------------------------
| Harus berada di luar middleware auth karena digunakan oleh pengguna
| yang belum login.
*/

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])
    ->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->middleware('throttle:5,1')
    ->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])
    ->name('password.reset');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.update');


/*
|--------------------------------------------------------------------------
| HALAMAN YANG WAJIB LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');


    /*
    |--------------------------------------------------------------------------
    | GENDER
    |--------------------------------------------------------------------------
    */

    Route::get('/gender', [PageController::class, 'gender'])
        ->name('gender');

    Route::post('/gender', [PageController::class, 'saveGender'])
        ->name('gender.save');


    /*
    |--------------------------------------------------------------------------
    | PROGRAMS
    |--------------------------------------------------------------------------
    */

    Route::get('/programs', [PageController::class, 'programs'])
        ->name('programs');

    Route::post('/programs/select', [PageController::class, 'selectProgram'])
        ->name('programs.select');


    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::post('/profile/edit', [ProfileController::class, 'update'])
        ->name('profile.update');


    /*
    |--------------------------------------------------------------------------
    | WORKOUT AJAX
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/workout/start-session',
        [WorkoutController::class, 'startSession']
    )->name('workout.startSession');

    Route::post(
        '/workout/save-progress',
        [WorkoutController::class, 'saveProgress']
    )->name('workout.saveProgress');

    Route::post(
        '/workout/finish-workout',
        [WorkoutController::class, 'finishWorkout']
    )->name('workout.finishWorkout');


    /*
    |--------------------------------------------------------------------------
    | HALAMAN DETAIL WORKOUT
    |--------------------------------------------------------------------------
    | Diletakkan setelah endpoint workout lainnya dan hanya menerima
    | gym, cardio, atau calisthenic.
    */

    Route::get('/workout/{type}', [PageController::class, 'workout'])
        ->whereIn('type', ['gym', 'cardio', 'calisthenic'])
        ->name('workout.detail');
});