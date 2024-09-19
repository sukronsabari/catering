<?php

use App\Http\Controllers;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', [Controllers\Home\HomeController::class,'index'])->name('home');

// Route::middleware('auth')->group(function () {
//     // Route::patch('/profile', [Controllers\Profile\ProfileController::class, 'update'])->name('profile.update');
//     // Route::patch('/profile/image', [Controllers\Profile\ProfileImageController::class, 'update'])->name('profile.image.update');
// });

Route::group(['prefix' => '/admin', 'middleware' => ['auth', 'role:admin']], function () {
    Route::get('/dashboard', [Controllers\Dashboard\AdminDashboardController::class, 'index'])->name('admin.dashboard.index');

    Route::get('/settings', [Controllers\Settings\AdminSettingsController::class, 'edit'])->name('admin.settings.edit');

    Route::put('/profile', [Controllers\Profile\ProfileController::class, 'update'])->name('admin.profile.update');
    Route::patch('/profile/image', [Controllers\Profile\ProfileImageController::class, 'update'])->name('admin.profile.image.update');

    Route::get('/sliders', [Controllers\Sliders\SlidersController::class, 'index'])->name('admin.sliders.index');
    Route::get('/sliders/new', [Controllers\Sliders\SlidersController::class, 'create'])->name('admin.sliders.create');
    Route::post('/sliders', [Controllers\Sliders\SlidersController::class, 'store'])->name('admin.sliders.store');
    Route::get('/sliders/{slider}/edit', [Controllers\Sliders\SlidersController::class, 'edit'])->name('admin.sliders.edit');
    Route::put('/sliders/{slider}', [Controllers\Sliders\SlidersController::class, 'update'])->name('admin.sliders.update');
    Route::delete('/sliders/{slider}', [Controllers\Sliders\SlidersController::class, 'destroy'])->name('admin.sliders.destroy');

    Route::get('/products', [Controllers\Products\ProductsController::class, 'index'])->name('admin.products.index');
    Route::get('/products/new', [Controllers\Products\ProductsController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [Controllers\Products\ProductsController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [Controllers\Products\ProductsController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [Controllers\Products\ProductsController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [Controllers\Products\ProductsController::class, 'destroy'])->name('admin.products.destroy');

    Route::get('/products/categories', [Controllers\Categories\CategoriesController::class, 'index'])->name('admin.products.categories.index');
    Route::get('/products/categories/new', [Controllers\Categories\CategoriesController::class, 'create'])->name('admin.products.categories.create');
    Route::post('/products/categories', [Controllers\Categories\CategoriesController::class, 'store'])->name('admin.products.categories.store');
    Route::get('/products/categories/{category}/edit', [Controllers\Categories\CategoriesController::class, 'edit'])->name('admin.products.categories.edit');
    Route::put('/products/categories/{category}', [Controllers\Categories\CategoriesController::class, 'update'])->name('admin.products.categories.update');
    Route::delete('/products/categories/{category}', [Controllers\Categories\CategoriesController::class, 'destroy'])->name('admin.products.categories.destroy');

    Route::get('/merchants', [Controllers\Merchants\MerchantsController::class, 'index'])->name('admin.merchants.index');
    Route::get('/merchants/new', [Controllers\Merchants\MerchantsController::class, 'create'])->name('admin.merchants.create');
    Route::post('/merchants', [Controllers\Merchants\MerchantsController::class, 'store'])->name('admin.merchants.store');
    Route::get('/merchants/{merchant}/edit', [Controllers\Merchants\MerchantsController::class, 'edit'])->name('admin.merchants.edit');
    Route::put('/merchants/{merchant}', [Controllers\Merchants\MerchantsController::class, 'update'])->name('admin.merchants.update');
    Route::delete('/merchants/{merchant}', [Controllers\Merchants\MerchantsController::class, 'destroy'])->name('admin.merchants.destroy');
});



Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
