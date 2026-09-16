<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\UserController;

// Dashboard
Route::get('/', [PageController::class, 'dashboard'])->name('dashboard');

// Announcements
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
Route::put('/notifications/{id}', [NotificationController::class, 'update'])->name('notifications.update');
Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
Route::get('/notifications/{id}/delete', [NotificationController::class, 'destroy'])->name('notifications.destroy.get');
Route::get('/notifications/{id}/readers', [NotificationController::class, 'readers'])->name('notifications.readers');

// User Management
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users/bulk-delete', [UserController::class, 'bulkDestroy'])->name('users.bulkDestroy');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');


