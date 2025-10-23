<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Home redirect based on role or to login if guest
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Auth routes
Route::controller(AuthController::class)->group(function () {
    // Login (guest only)
    Route::get('/login', 'showLogin')->name('login')->middleware('guest');
    Route::post('/login', 'login')->name('login.post');

    // Logout (auth only)
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');

    // TEMP: Open registration to everyone for sample user creation during setup
    // NOTE: Remove guest access later and restore admin-only protection when going live
    Route::get('/register', 'showRegister')->name('register')->middleware('guest');
    Route::post('/register', 'register')->name('register.post')->middleware('guest');
});

// Admin routes (protected)
Route::prefix('admin')
    ->middleware(['web','auth','admin'])
    ->as('admin.')
    ->group(function () {
        Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
        Route::get('/elections', fn () => view('admin.elections.index'))->name('elections.index');
        Route::get('/parties', fn () => view('admin.parties.index'))->name('parties.index');
        Route::get('/candidates', fn () => view('admin.candidates.index'))->name('candidates.index');
    });

// Student routes (protected)
Route::prefix('student')
    ->middleware(['web','auth','student'])
    ->as('student.')
    ->group(function () {
        Route::get('/dashboard', fn () => view('student.dashboard'))->name('dashboard');
        Route::get('/vote', fn () => view('student.vote'))->name('vote');
    });
