<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LostItemController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/* ── หน้าสาธารณะ ── */
Route::get('/', [PublicController::class, 'index'])->name('home');

/* ── Auth ── */
Route::get('/login', fn() => view('auth.login'))->name('login')->middleware('guest');
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');
Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');

/* ── Admin (ต้อง login) ── */
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    /* Lost Items */
    Route::resource('lost-items', LostItemController::class)->parameters(['lost-items' => 'lostItem']);
    Route::post('lost-items/{lostItem}/return',       [LostItemController::class, 'returnItem'])->name('lost-items.return');
    Route::post('lost-items/{lostItem}/toggle-image', [LostItemController::class, 'toggleImage'])->name('lost-items.toggle-image');

    /* Categories */
    Route::resource('categories', CategoryController::class)->parameters(['categories' => 'category']);
    Route::post('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');

    /* Locations */
    Route::resource('locations', LocationController::class)->parameters(['locations' => 'location']);
    Route::post('locations/{location}/toggle-status', [LocationController::class, 'toggleStatus'])->name('locations.toggle-status');

    /* Staffs */
    Route::resource('staffs', StaffController::class)->parameters(['staffs' => 'staff']);
    Route::post('staffs/{staff}/toggle-status', [StaffController::class, 'toggleStatus'])->name('staffs.toggle-status');

    /* Settings */
    Route::get('settings',  [SettingController::class, 'edit'])->name('settings');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

    /* Export */
    Route::post('export', [ExportController::class, 'export'])->name('export');

    /* Users — ดูรายการได้ทุก role, แก้ไข/เพิ่ม/ลบ เฉพาะ admin */
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::resource('users', UserController::class)->parameters(['users' => 'user'])
         ->except(['index'])->middleware('admin_only');
});
