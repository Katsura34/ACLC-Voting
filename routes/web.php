<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.post');
    Route::post('/logout', 'logout')->name('logout');
    
    // Registration (for admin use)
    Route::get('/register', 'showRegister')->name('register')->middleware('admin');
    Route::post('/register', 'register')->name('register.post')->middleware('admin');
});

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Elections routes
    Route::get('/elections', function () {
        return view('admin.elections.index');
    })->name('elections.index');
    
    // Parties routes
    Route::get('/parties', function () {
        return view('admin.parties.index');
    })->name('parties.index');
    
    // Candidates routes
    Route::get('/candidates', function () {
        return view('admin.candidates.index');
    })->name('candidates.index');
});

// Student routes
Route::prefix('student')->middleware(['auth', 'student'])->name('student.')->group(function () {
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('dashboard');
    
    Route::get('/vote', function () {
        return view('student.vote');
    })->name('vote');
});