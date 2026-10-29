<?php

use App\Http\Controllers\SuperadminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Built-in Super Admin routes
|--------------------------------------------------------------------------
|
| Lightweight replacement for the paid "Superadmin" module. These routes are
| only loaded by App\Providers\RouteServiceProvider when the directory
| Modules/Superadmin does NOT exist, so they never clash with the real module.
|
| Access requires:
|   1. Being logged in, and
|   2. Your username being listed in ADMINISTRATOR_USERNAMES in .env
|      (enforced by the `superadmin` middleware).
|
*/

Route::middleware(['setData', 'auth', 'SetSessionData', 'superadmin', 'language', 'timezone', 'AdminSidebarMenu'])
    ->prefix('superadmin')
    ->name('core_superadmin.')
    ->group(function () {
        Route::get('/', [SuperadminController::class, 'index'])->name('index');

        Route::get('/businesses', [SuperadminController::class, 'businesses'])->name('businesses');
        Route::get('/businesses/{id}', [SuperadminController::class, 'showBusiness'])->name('businesses.show');
        Route::post('/businesses/{id}/toggle-active', [SuperadminController::class, 'toggleBusinessActive'])->name('businesses.toggle_active');

        Route::get('/users', [SuperadminController::class, 'users'])->name('users');
        Route::post('/users/{id}/toggle-status', [SuperadminController::class, 'toggleUserStatus'])->name('users.toggle_status');
    });
