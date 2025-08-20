<?php

use App\Http\Controllers\Owner\Owner\OwnerActivityController;
use App\Http\Controllers\Owner\Owner\OwnerHomeController;
use App\Http\Controllers\Owner\Owner\OwnerProgrammeController;
use App\Http\Controllers\Owner\Owner\OwnerStatController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:owner'])->group(function () {
    Route::get('owner/home', [OwnerHomeController::class, 'index'])
        ->name('owner.home');
    Route::get('owner/edit', [OwnerHomeController::class, 'edit'])
        ->name('owner.edit.school');
    Route::post('owner/edit', [OwnerHomeController::class, 'update'])
        ->name('owner.update.school');

    Route::get('owner/about', [OwnerHomeController::class, 'about'])
        ->name('owner.about.school');
    Route::post('owner/about', [OwnerHomeController::class, 'aboutUpdate'])
        ->name('owner.aboutUpdate.school');

    Route::get('owner/editInfo', [OwnerHomeController::class, 'editInfo'])
        ->name('owner.editInfo.school');
    Route::post('owner/editInfo', [OwnerHomeController::class, 'updateInfo'])
        ->name('owner.updateInfo.school');

    Route::get('owner/show', [OwnerHomeController::class, 'show'])
        ->name('owner.show.school');

    Route::get('owner/gallery', [OwnerHomeController::class, 'Gallery'])
        ->name('owner.gallery.school');
    Route::post('owner/gallery', [OwnerHomeController::class, 'storeGallery'])
        ->name('owner.storegallery.school');
    Route::delete('/images/{image}', [OwnerHomeController::class, 'destroyGallery'])->name('images.destroy');

    Route::get('owner/testimonials', [OwnerHomeController::class, 'Testimonials'])
        ->name('owner.testimonials.school');
    Route::put('/owner/testimonials/{testimonial}/toggle', [OwnerHomeController::class, 'toggle'])
        ->name('owner.testimonials.toggle');
    Route::delete('/owner/testimonials/{testimonial}', [OwnerHomeController::class, 'destroy'])
        ->name('owner.testimonials.destroy');

    Route::get('owner/stat', [OwnerStatController::class, 'index'])
        ->name('owner.stat');

    Route::get('owner/formation/{status}', [OwnerProgrammeController::class, 'indexByStatus'])
        ->whereIn('status', ['published', 'draft', 'archived'])
        ->name('owner.formation.status');
    Route::get('owner/formation/add', [OwnerProgrammeController::class, 'add'])
        ->name('owner.formation.add');
    Route::post('owner/formation/add', [OwnerProgrammeController::class, 'store'])
        ->name('owner.formation.store');
    Route::get('owner/formation/edit/{slug}', [OwnerProgrammeController::class, 'edit'])
        ->name('owner.formation.edit');
    Route::post('owner/formation/edit/{slug}', [OwnerProgrammeController::class, 'update'])
        ->name('owner.formation.update');
    Route::get('owner/formation/show/{slug}', [OwnerProgrammeController::class, 'show'])
        ->name('owner.formation.show');
    Route::put('owner/formation/published/{slug}', [OwnerProgrammeController::class, 'published'])
        ->name('owner.formation.published');
    Route::put('owner/formation/draft/{slug}', [OwnerProgrammeController::class, 'draft'])
        ->name('owner.formation.draft');
    Route::put('owner/formation/archived/{slug}', [OwnerProgrammeController::class, 'archived'])
        ->name('owner.formation.archived');
    Route::delete('/owner/formations/{formation}', [OwnerProgrammeController::class, 'destroy'])
        ->name('owner.formations.destroy');


    Route::get('owner/activity/{status}', [OwnerActivityController::class, 'indexByStatus'])
        ->whereIn('status', ['published', 'draft', 'archived'])
        ->name('owner.activity.status');
    Route::get('owner/activity/add', [OwnerActivityController::class, 'add'])
        ->name('owner.activity.add');
    Route::post('owner/activity/add', [OwnerActivityController::class, 'store'])
        ->name('owner.activity.store');
    Route::get('owner/activity/edit/{slug}', [OwnerActivityController::class, 'edit'])
        ->name('owner.activity.edit');
    Route::post('owner/activity/edit/{slug}', [OwnerActivityController::class, 'update'])
        ->name('owner.activity.update');
    Route::get('owner/activity/show/{slug}', [OwnerActivityController::class, 'show'])
        ->name('owner.activity.show');
    Route::put('owner/activity/published/{slug}', [OwnerActivityController::class, 'published'])
        ->name('owner.activity.published');
    Route::put('owner/activity/draft/{slug}', [OwnerActivityController::class, 'draft'])
        ->name('owner.activity.draft');
    Route::put('owner/activity/archived/{slug}', [OwnerActivityController::class, 'archived'])
        ->name('owner.activity.archived');
    Route::delete('/owner/activitys/{activity}', [OwnerActivityController::class, 'destroy'])
        ->name('owner.activity.destroy');
});
