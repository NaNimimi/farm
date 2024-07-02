<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MoneyController;


use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\CategoryController;

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

// Order маршруты
Route::middleware('auth:api')->group(function () {
    Route::get('/orders', [OrderController::class, 'allOrders']);
    Route::post('/orders', [OrderController::class, 'createOrder']);
    Route::put('/orders/{id}', [OrderController::class, 'updateOrder']);
    Route::delete('/orders/{id}', [OrderController::class, 'deleteOrder']);
});

// Doctor маршруты
Route::middleware('auth:api')->group(function () {
    Route::get('/doctors', [DoctorController::class, 'getAllDoctors']);
    Route::post('/doctors', [DoctorController::class, 'addDoctor']);
    Route::delete('/doctors/{id}', [DoctorController::class, 'deleteDoctor']);
    Route::put('/doctors/{id}', [DoctorController::class, 'updateDoctor']);
});

// Service маршруты
Route::middleware('auth:api')->group(function () {
    Route::get('/services', [ServiceController::class, 'getAllServices']);
    Route::post('/services', [ServiceController::class, 'addService']);
    Route::delete('/services/{id}', [ServiceController::class, 'deleteService']);
    Route::put('/services/{id}', [ServiceController::class, 'updateService']);
});

// Money маршруты
Route::middleware('auth:api')->group(function () {
    Route::get('/cashbox', [MoneyController::class, 'showCashBox']);
    Route::get('/inkassa', [MoneyController::class, 'inkassa']);
});

// Role контроллеры
Route::middleware('auth:api')->group(function () {
    Route::get('/roles', [RoleController::class, 'index']);

    // Route to create a new role
    Route::post('/roles', [RoleController::class, 'store']);
    
    // Route to delete a role
    Route::delete('/roles/{roleId}', [RoleController::class, 'destroy']);
    
    // Route to assign a role to a user
    Route::post('/users/{userId}/roles', [RoleController::class, 'assignRole']);
    
    // Route to revoke a role from a user
    Route::delete('/users/{userId}/roles', [RoleController::class, 'revokeRole']);

});

// Admin маршруты
Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/admin-dashboard', [PageController::class, 'adminDashboard']);
    // другие админ маршруты
});

// User маршруты
Route::middleware(['auth:api', 'role:user'])->group(function () {
    Route::get('/user-dashboard', [PageController::class, 'userDashboard']);
    // другие пользовательские маршруты
});

// Public маршруты
Route::get('/public-page', [PageController::class, 'publicPage']);


// Маршрут для получения ролей пользователя
// user controller
Route::middleware('auth:api')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{userId}/roles', [UserController::class, 'getUserRoles']);
});



Route::middleware('auth:api')->group(function () {
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::post('/customers/create', [CustomerController::class, 'store']);

    Route::put('/customers/{id}', [CustomerController::class, 'update']);
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);
});

// In routes/api.php
Route::middleware('auth:api')->group(function () {
    Route::get('/categories', [MainController::class, 'index']);
    Route::post('/categories', [MainController::class, 'store']);
    Route::put('/categories/{id}', [MainController::class, 'update']);
    Route::delete('/categories/{id}', [MainController::class, 'destroy']);
});


