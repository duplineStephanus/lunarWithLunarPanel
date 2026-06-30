<?php

use App\Livewire\Storefront\ProductShow;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');

Route::get('products/{slug}', ProductShow::class)->name('storefront.products.show');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
