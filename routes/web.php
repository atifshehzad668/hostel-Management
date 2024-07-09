<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;

use App\Http\Controllers\FloorController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\ExpenseHeadController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\RecivedPaymentController;
use App\Http\Controllers\StaffController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('admin/login', [AdminLoginController::class, 'index'])->name('admin.login');
// Route::post('admin/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');



Route::prefix('admin')->group(function () {
    Route::middleware('admin.guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');




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


        // Fee Routes

        Route::get('/fee/index', [FeeController::class, 'index'])->name('fee.index');
        Route::get('/generate/fee/{id}', [FeeController::class, 'generate_fee'])->name('fee.generate');
        Route::post('/fee/store', [FeeController::class, 'store_fee'])->name('fee.insert');
        Route::get('/user_fee/list', [FeeController::class, 'user_fee_list'])->name('user_fee.list');
        Route::get('/user_fee/edit/{id}', [FeeController::class, 'user_fee_edit'])->name('user_fee.edit');
        Route::post('/user_fee/update/{id}', [FeeController::class, 'user_fee_update'])->name('user_fee.update');
        Route::delete('/user_fee/destroy/{id}', [FeeController::class, 'user_fee_destroy'])->name('user_fee.destroy');



        //recived payments
        // Route::post('/payment/store', [RecivedPaymentController::class, 'store'])->name('payment.store');
        // Route::get('/getfee/detail/{id}', [RecivedPaymentController::class, 'feedetail'])->name('getfee.detail');






        // expense_head route start
        Route::get('expense/head/create', [ExpenseHeadController::class, 'create'])->name('expense_head.create');
        Route::post('expense/head/store', [ExpenseHeadController::class, 'store'])->name('expense_head.store');
        Route::get('expense/head/index', [ExpenseHeadController::class, 'index'])->name('expense_head.index');
        Route::get('expense/head/edit/{id}', [ExpenseHeadController::class, 'edit'])->name('expense_head.edit');
        Route::post('expense/head/update/{id}', [ExpenseHeadController::class, 'update'])->name('expense_head.update');
        Route::delete('expense/head/destroy/{id}', [ExpenseHeadController::class, 'destroy'])->name('expense_head.destroy');
        Route::get('expense/head/trashindex', [ExpenseHeadController::class, 'trashindex'])->name('expense_head.trashindex');
        Route::get('expense/head/restore/{id}', [ExpenseHeadController::class, 'restore'])->name('expense_head.restore');
        Route::get('expense/head/permanentdelete/{id}', [ExpenseHeadController::class, 'permanentDelete'])->name('expense_head.permanentdelete');

        // expense_head route end






        // expense route start
        Route::get('expense/create', [ExpenseController::class, 'create'])->name('expense.create');
        Route::post('expense/store', [ExpenseController::class, 'store'])->name('expense.store');
        Route::get('expense/index', [ExpenseController::class, 'index'])->name('expense.index');
        Route::get('expense/edit/{id}', [ExpenseController::class, 'edit'])->name('expense.edit');
        Route::post('expense/update/{id}', [ExpenseController::class, 'update'])->name('expense.update');
        Route::delete('expense/delete/{id}', [ExpenseController::class, 'destroy'])->name('expense.destroy');
        Route::get('expense/trashindex', [ExpenseController::class, 'trashindex'])->name('expense.trashindex');
        Route::get('expense/restore/{id}', [ExpenseController::class, 'restore'])->name('expense.restore');
        Route::get('expense/permanentdelete/{id}', [ExpenseController::class, 'permanentDelete'])->name('expense.permanentdelete');

        // expense route end

        //staff route
        Route::get('staff/create', [StaffController::class, 'create'])->name('staff.create');
        Route::post('staff/store', [StaffController::class, 'store'])->name('staff.store');
        Route::get('staff/index', [StaffController::class, 'index'])->name('staff.index');
        Route::get('staff/edit/{id}', [StaffController::class, 'edit'])->name('staff.edit');
        Route::post('staff/update/{id}', [StaffController::class, 'update'])->name('staff.update');
        Route::delete('staff/delete/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
    });
});














Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__ . '/auth.php';