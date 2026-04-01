<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('home');
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::view('/registration_page', 'welcome')->name('registration.page');
Route::get('/register-user', [RegisteredUserController::class, 'createUser'])->name('register.user');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');

    // Route::get('/events', function () {return view('events');})->name('events');
    Route::get('/worship-team', function () {return view('worship-team');})->name('worship-team');
    Route::get('/worship-schedule', function () {return view('worship-schedule');})->name('worship-schedule');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
