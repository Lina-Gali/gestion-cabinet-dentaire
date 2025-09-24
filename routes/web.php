<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RendezVousController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('patients')->group(function(){
    Route::get('/search', [PatientController::class, 'search'])->name('patients.search'); 
    Route::get('/',[PatientController::class, 'index'])->name('patients.index');
    Route::get('/create',[PatientController::class, 'create'])->name('patients.create');
    Route::post('/',[PatientController::class, 'store'])->name('patients.store');
    Route::get('/{id}',[PatientController::class, 'show'])->name('patients.show');
    Route::get('/{id}/edit',[PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/{id}',[PatientController::class, 'update'])->name('patients.update');
    Route::delete('/{id}',[PatientController::class, 'destroy'])->name('patients.destroy');
});

Route::prefix('rendez-vous')->group(function(){
    Route::get('/',[RendezVousController::class, 'index'])->name('rendezvous.index');
    Route::get('/create',[RendezVousController::class, 'create'])->name('rendezvous.create');
    Route::post('/',[RendezVousController::class, 'store'])->name('rendezvous.store');
    Route::get('/calendar', [RendezVousController::class, 'calendar'])->name('rendezvous.calendar');
    Route::get('/{id}/edit',[RendezVousController::class, 'edit'])->name('rendezvous.edit');
    Route::put('/{id}',[RendezVousController::class, 'update'])->name('rendezvous.update');
    Route::delete('/{id}',[RendezVousController::class, 'destroy'])->name('rendezvous.destroy');
});


require __DIR__.'/auth.php';
