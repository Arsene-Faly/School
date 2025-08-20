<?php

use App\Http\Controllers\FaqController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('home');

Route::get('dash', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'role:admin'])->name('dashboard');

Route::middleware(['auth', 'role:admin,owner,pending_owner'])->group(function () {
    Route::get("/faqDash", [FaqController::class, "index"])->name('faq.admin');
});

Route::middleware('auth')->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/mark-as-read', [MessageController::class, 'markAsRead'])
        ->name('messages.markAsRead');
});

require __DIR__ . '/ownerpending.php';
require __DIR__ . '/owner.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/user.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

Route::fallback(function () {
    if (Auth::check()) {
        $role = Auth::user()->role;

        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'owner':
                return redirect()->route('owner.home');
            case 'pending_owner':
                return redirect()->route('owner.pending');
            default:
                return redirect()->route('home');
        }
    }

    return redirect('/login');
});
