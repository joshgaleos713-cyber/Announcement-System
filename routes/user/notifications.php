<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\PageController;

// Dashboard
Route::get('/', [PageController::class, 'dashboard'])->name('dashboard');

// Announcements
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::match(['get', 'post'], '/notifications/mark-read', [NotificationController::class, 'markRead'])->name('notifications.markRead');
Route::match(['get', 'post'], '/notifications/{id}/read', [NotificationController::class, 'markSingleRead'])->name('notifications.markSingleRead');
Route::get('/notifications/api/unread-feed', [NotificationController::class, 'unreadFeed'])->name('notifications.unreadFeed');
