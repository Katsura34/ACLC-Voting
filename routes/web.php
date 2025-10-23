<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
*/

// Redirect root to login or dashboard based on auth
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Authentication routes (guest only for login page)
Route::middleware('web')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'showLogin')->name('login')->middleware('guest');
        Route::post('/login', 'login')->name('login.post');
        Route::post('/logout', 'logout')->name('logout')->middleware('auth');

        // Registration (for admin use)
        Route::get('/register', 'showRegister')->name('register')->middleware(['auth','admin']);
        Route::post('/register', 'register')->name('register.post')->middleware(['auth','admin']);
    });
});

// Admin routes
Route::prefix('admin')
    ->middleware(['web','auth','admin'])
    ->as('admin.')
    ->group(function () {
        Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

        // Elections
        Route::get('/elections', fn () => view('admin.elections.index'))->name('elections.index');
        // Parties
        Route::get('/parties', fn () => view('admin.parties.index'))->name('parties.index');
        // Candidates
        Route::get('/candidates', fn () => view('admin.candidates.index'))->name('candidates.index');
    });

// Student routes
Route::prefix('student')
    ->middleware(['web','auth','student'])
    ->as('student.')
    ->group(function () {
        Route::get('/dashboard', fn () => view('student.dashboard'))->name('dashboard');
        Route::get('/vote', fn () => view('student.vote'))->name('vote');
    });
