<?php

use App\Http\Controllers\User\AboutController;
use App\Http\Controllers\User\ContactController;
use App\Http\Controllers\User\EtablissementController;
use App\Http\Controllers\User\FaqController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ProfileUserController;
use App\Http\Controllers\User\ProgrammeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'storeContact'])->name('contact.add');

Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::post('/about', [AboutController::class, 'storeTestimonials'])->name('testimonial.add');

Route::get('/faq', [FaqController::class, 'index'])->name('faq');


// USER ETABLISSEMENT
Route::middleware(['auth', 'role:user,admin'])->group(function () {
    Route::get('/etablissement/{categorie}', [EtablissementController::class, 'index'])->name('etablissement.categorie');
    Route::get('/etablissement/detail/{slug}', [EtablissementController::class, 'show'])->name('etablissement.show');
    Route::post('/ecoles/{id}/rate', [EtablissementController::class, 'rate'])->middleware('auth');

    Route::get('/etablissement/detail/Testimonial/{slug}', [EtablissementController::class, 'Testimonials'])->name('etablissement.add.testimonial');
    Route::post('/etablissement/detail/Testimonial/store', [EtablissementController::class, 'storeTestimonial'])->name('etablissement.store.testimonial');

    Route::post('/schools/{id}/increment-views', [EtablissementController::class, 'incrementViews']);

    Route::get('/programme/{categorie}', [ProgrammeController::class, 'index'])->name('programme.categorie');

    Route::get('/programme/{categorie}/{slug}', [ProgrammeController::class, 'show'])
        ->name('programme.show');

    Route::post('/etablissement/{school}/favorite', [EtablissementController::class, 'toggleFavorite'])
        ->name('schools.favorite');

    Route::get('/mes-favoris', [EtablissementController::class, 'favoris'])->name('user.favoris');

    Route::delete('/favoris/delete/{favoris}', [EtablissementController::class, 'destroy'])
        ->name('favorite.destroy');
});

Route::post('/ecoles/{id}/rate', [EtablissementController::class, 'rate'])->middleware('auth');

// LES PROFILS
Route::middleware(['auth', 'role:user,owner,pending_owner'])->group(function () {
    Route::get('/profil/user', [ProfileUserController::class, 'index'])->name('profile.user');
    Route::patch('/profil/user', [ProfileUserController::class, 'update'])->name('profile.user.update');

    Route::get('/profil/password', [ProfileUserController::class, 'editPassword'])->name('profile.password.edit');

    Route::get('/profil/detail', [ProfileUserController::class, 'editProfileDetail'])->name('profile.detail');
    Route::post('/profil/detail', [ProfileUserController::class, 'updateProfileDetail'])->name('profile.detail.update');

    Route::get('/profil/show', [ProfileUserController::class, 'show'])->name('profile.show');
});
