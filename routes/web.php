<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('rooms', 'rooms')->name('rooms');
Route::view('onsen', 'onsen')->name('onsen');
Route::view('facilities', 'facilities')->name('facilities');
Route::view('memberships', 'memberships')->name('memberships');
Route::view('faq', 'faq')->name('faq');
Route::view('about', 'about')->name('about');

Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
