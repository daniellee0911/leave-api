<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');


Route::middleware('auth:sanctum')->group(function() {
    Route::get('/auth/user', [AuthController::class, 'user'])->name('auth.user');
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
});


Route::middleware(['auth:sanctum','admin'])->group(function() {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user_id}', [UserController::class, 'show'])->name('users.show');

    Route::post('/users', [UserController::class, 'create'])->name('users.create');

    Route::put('/users/{user_id}/profile', [UserController::class, 'updateProfile'])->name('users.update.profile');
    Route::put('/users/{user_id}/password', [UserController::class, 'updatePassword'])->name('users.update.password');
    Route::put('/users/{user_id}/email', [UserController::class, 'updateEmail'])->name('users.update.email');

    Route::delete('/users/{user_id}', [UserController::class, 'delete'])->name('users.delete');
});   
