<?php

use App\Http\Controllers\Owner\OwnerPending\StatutPendingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:pending_owner'])->group(function () {
    Route::get('/owner/pending', [StatutPendingController::class, 'index'])
        ->name('owner.pending');

    Route::post('/owner/pending/store', [StatutPendingController::class, 'store'])
        ->name('owner.pending.store');
});
