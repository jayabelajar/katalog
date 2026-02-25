<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\MarketplaceLinkController;

Route::get('/', function () {
    return view('frontend.home');
})->name('home');

Route::get('/kategori', function () {
    return view('frontend.kategori');
})->name('kategori');

Route::get('/kategori/{slug}', function ($slug) {
    return view('frontend.kategori-detail', compact('slug'));
})->name('kategori.detail');

Route::get('/produk', function () {
    return view('frontend.produk');
})->name('katalog');

Route::redirect('/katalog', '/produk', 301);

Route::get('/produk/{slug}', function ($slug) {
    return view('frontend.detail', compact('slug'));
})->name('produk.detail');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/setting', SettingController::class)->only(['index', 'store', 'update']);
    Route::resource('/kategori', CategoryController::class);
    Route::resource('/produk', ProductController::class);
    Route::resource('/marketplace-link', MarketplaceLinkController::class);
});
