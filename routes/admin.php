<?php

use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MembreController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\TestimonialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('admin/membre/{role}', [MembreController::class, 'index'])
        ->name('admin.membre')
        ->where('role', 'user|owner|pending_owner');

    Route::delete('/admin/membre/{user}', [MembreController::class, 'destroy'])->name('admin.membre.destroy');

    Route::get('/admin/ecole', fn(Request $request) => app(SchoolController::class)->index($request, 'validated'))
        ->name('admin.ecole');
    Route::get('/admin/ecole-en-attente', fn(Request $request) => app(SchoolController::class)->index($request, 'pending'))
        ->name('admin.ecole.pending');

    Route::delete('/admin/ecole{ecole}', [SchoolController::class, 'destroy'])
        ->name('admin.ecole.delete');
    Route::put('/admin/ecole/{id}/valider', [SchoolController::class, 'valider'])->name('ecoles.valider');


    Route::get('admin/testimonial', [TestimonialController::class, 'index'])
        ->name('admin.testimonial');
    Route::put('/admin/testimonials/{testimonial}/toggle', [TestimonialController::class, 'toggle'])
        ->name('admin.testimonials.toggle');
    Route::delete('/admin/testimonials/{testimonial}', [TestimonialController::class, 'destroy'])
    ->name('admin.testimonials.destroy');

    Route::get('admin/contact', [ContactController::class, 'index'])
        ->name('admin.contact');
    Route::delete('/admin/contact/{contact}', [ContactController::class, 'destroy'])
    ->name('admin.contact.destroy');

    Route::get('/admin/notifications', [DashboardController::class, 'notificationViews'])->name('admin.notifications');
    Route::delete('/admin/notifications/{id}', [DashboardController::class, 'destroy'])->name('admin.notifications.destroy');
});
