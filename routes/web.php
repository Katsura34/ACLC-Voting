<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('student.dashboard');
    }

    return redirect()->route('login');
})->name('home');

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login')->middleware('guest');
    Route::post('/login', 'login')->name('login.post');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
    Route::get('/register', 'showRegister')->name('register')->middleware('guest');
    Route::post('/register', 'register')->name('register.post')->middleware('guest');
});

Route::prefix('admin')
    ->middleware(['web', 'auth', 'admin'])
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
        Route::post('/elections/{election}/toggle', [ElectionController::class, 'toggle'])->name('elections.toggle');
        Route::post('/elections/{election}/publish', [ElectionController::class, 'publishResults'])->name('elections.publish');
        Route::post('/elections/{election}/reset', [ElectionController::class, 'resetVotes'])->name('elections.reset');

        // Positions (nested under election)
        Route::post('/elections/{election}/positions', [PositionController::class, 'store'])->name('positions.store');
        Route::put('/elections/{election}/positions/{position}', [PositionController::class, 'update'])->name('positions.update');
        Route::delete('/elections/{election}/positions/{position}', [PositionController::class, 'destroy'])->name('positions.destroy');

        // Parties (nested under election)
        Route::post('/elections/{election}/parties', [PartyController::class, 'store'])->name('parties.store');
        Route::put('/elections/{election}/parties/{party}', [PartyController::class, 'update'])->name('parties.update');
        Route::delete('/elections/{election}/parties/{party}', [PartyController::class, 'destroy'])->name('parties.destroy');

        // Candidates (nested under election)
        Route::post('/elections/{election}/candidates', [CandidateController::class, 'store'])->name('candidates.store');
        Route::put('/elections/{election}/candidates/{candidate}', [CandidateController::class, 'update'])->name('candidates.update');
        Route::delete('/elections/{election}/candidates/{candidate}', [CandidateController::class, 'destroy'])->name('candidates.destroy');

        // Non-nested convenience endpoints for forms that don't know the election at render time
        Route::post('/parties/store', [PartyController::class, 'storeAny'])->name('parties.storeAny');
        Route::post('/candidates/store', [CandidateController::class, 'storeAny'])->name('candidates.storeAny');

        // Pages
        Route::get('/parties', fn () => view('admin.parties.index'))->name('parties.index');
        Route::get('/parties/create', fn () => view('admin.parties.create'))->name('parties.create');
        Route::get('/candidates', fn () => view('admin.candidates.index'))->name('candidates.index');
    });

Route::prefix('student')
    ->middleware(['web', 'auth', 'student'])
    ->as('student.')
    ->group(function () {
        Route::get('/dashboard', fn () => view('student.dashboard'))->name('dashboard');
        Route::get('/vote', [VoteController::class, 'show'])->name('vote');
        Route::post('/vote', [VoteController::class, 'submit'])->name('vote.submit');
    });
