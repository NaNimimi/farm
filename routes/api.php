<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MoneyController;
use App\Http\Controllers\PageController;
 

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::get('test', function () {
        return response()->json(['message' => 'API route is working']);
    });
});

// Order routerlar
Route::middleware('auth:api')->group(function () {
    Route::get('/orders', [OrderController::class, 'allOrders']);
    Route::post('/orders', [OrderController::class, 'createOrder']);
    Route::put('/orders/{id}', [OrderController::class, 'updateOrder']);
    Route::delete('/orders/{id}', [OrderController::class, 'deleteOrder']);
});

// Doctor routerlar
Route::middleware('auth:api')->group(function () {
    Route::get('/doctors', [DoctorController::class, 'getAllDoctors']);
    Route::post('/doctors', [DoctorController::class, 'addDoctor']);
    Route::delete('/doctors/{id}', [DoctorController::class, 'deleteDoctor']);
    Route::put('/doctors/{id}', [DoctorController::class, 'updateDoctor']);
});

// Service routerlar
Route::middleware('auth:api')->group(function () {
    Route::get('/services', [ServiceController::class, 'getAllServices']);
    Route::post('/services', [ServiceController::class, 'addService']);
    Route::delete('/services/{id}', [ServiceController::class, 'deleteService']);
    Route::put('/services/{id}', [ServiceController::class, 'updateService']);
});

// Money routerlar
Route::middleware('auth:api')->group(function () {
    Route::get('/cashbox', [MoneyController::class, 'showCashBox']);
});

// Role controrellar
Route::middleware('auth:api')->group(function () {
    Route::post('/assign-role/{userId}', [RoleController::class, 'assignRole']);
    Route::post('/revoke-role/{userId}', [RoleController::class, 'revokeRole']);
});

// Admin Marshuruti
Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/admin-dashboard', [PageController::class, 'adminDashboard']);
    // admini boshqa marshuruti
});

// odi odamni marshururti
Route::middleware(['auth:api', 'role:user'])->group(function () {
    Route::get('/user-dashboard', [PageController::class, 'userDashboard']);
    //  odi  odamni boshqa marshururti
});

// lyubou odamni marshuruti
Route::get('/public-page', [PageController::class, 'publicPage']);
// user controller
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');