<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/floor/create', [FloorController::class, 'create'])->name('floor.create');
Route::post('/floor/store', [FloorController::class, 'store'])->name('floor.store');
Route::get('/floor/index', [FloorController::class, 'index'])->name('floor.index');
Route::delete('/floor/destroy/{id}', [FloorController::class, 'destroy'])->name('floor.destroy');
Route::get('/floor/edit/{id}', [FloorController::class, 'edit'])->name('floor.edit');
Route::post('/floor/update/{id}', [FloorController::class, 'update'])->name('floor.update');

Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
Route::get('/rooms/index', [RoomController::class, 'index'])->name('rooms.index');
Route::post('/rooms/store', [RoomController::class, 'store'])->name('rooms.store');
Route::get('/rooms/edit/{id}', [RoomController::class, 'edit'])->name('rooms.edit');
Route::delete('/rooms/destroy/{id}', [RoomController::class, 'destroy'])->name('rooms.destroy');
Route::post('/rooms/update/{id}', [RoomController::class, 'update'])->name('rooms.update');

Route::get('/registration/create', [RegistrationController::class, 'create'])->name('registration.create');
Route::post('/registration/store', [RegistrationController::class, 'store'])->name('registration.store');
Route::get('/registration/index', [RegistrationController::class, 'index'])->name('registration.index');
Route::get('/registration/edit/{id}', [RegistrationController::class, 'edit'])->name('registration.edit');
Route::delete('/registration/destroy/{id}', [RegistrationController::class, 'destroy'])->name('registration.destroy');
Route::post('/registration/update/{id}', [RegistrationController::class, 'update'])->name('registration.update');
Route::get('/registration/view/{id}', [RegistrationController::class, 'view'])->name('registration.view');
Route::get('/filter/rooms', [RegistrationController::class, 'filter'])->name('filter.rooms');





Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';