<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ElectionController;
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
    Route::get('/register', 'showRegister')->name('register')->middleware('guest');
    Route::post('/register', 'register')->name('register.post')->middleware('guest');
});

// Admin routes (protected)
Route::prefix('admin')
    ->middleware(['web','auth','admin'])
    ->as('admin.')
    ->group(function () {
        Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

        // Elections CRUD
        Route::get('/elections', [ElectionController::class, 'index'])->name('elections.index');
        Route::get('/elections/create', [ElectionController::class, 'create'])->name('elections.create');
        Route::post('/elections', [ElectionController::class, 'store'])->name('elections.store');
        Route::get('/elections/{election}', [ElectionController::class, 'show'])->name('elections.show');
        Route::get('/elections/{election}/edit', [ElectionController::class, 'edit'])->name('elections.edit');
        Route::put('/elections/{election}', [ElectionController::class, 'update'])->name('elections.update');
        Route::delete('/elections/{election}', [ElectionController::class, 'destroy'])->name('elections.destroy');

        // Actions
        Route::post('/elections/{election}/toggle', [ElectionController::class, 'toggle'])->name('elections.toggle');
        Route::post('/elections/{election}/publish', [ElectionController::class, 'publishResults'])->name('elections.publish');
        Route::post('/elections/{election}/reset', [ElectionController::class, 'resetVotes'])->name('elections.reset');

        // Parties
        Route::get('/parties', fn () => view('admin.parties.index'))->name('parties.index');
        // Candidates
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
