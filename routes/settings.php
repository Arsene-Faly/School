<?php

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\PasswordNewController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\ProfileNewController;
use App\Http\Controllers\Settings\ProfileNewDetailController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('settings/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('settings/appearance', function () {
        return Inertia::render('settings/Appearance');
    })->name('appearance');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('settings/profileNew', [ProfileNewController::class, 'edit'])->name('profileNew.edit');
    Route::patch('settings/profileNew', [ProfileNewController::class, 'update'])->name('profileNew.update');

    Route::get('settings/profileNewDetail', [ProfileNewDetailController::class, 'edit'])->name('profileNewDetail.edit');
    Route::post('settings/profileNewDetail', [ProfileNewDetailController::class, 'update'])->name('profileNewDetail.update');

    Route::get('settings/passwordNew', [PasswordNewController::class, 'edit'])->name('passwordNew.edit');

    Route::get('settings/profileShow', [ProfileNewDetailController::class, 'show'])->name('profileNew.show');
});
