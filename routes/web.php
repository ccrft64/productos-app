<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CustomRegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'canRegisterAdmin' => Route::has('admin.register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('guest')->group(function () {

    Route::get('/register', [CustomRegisterController::class, 'create'])->name('register');

    Route::get('/register-admin', function () {
        return redirect()->route('custom.register.form', ['roleType' => 'admin']);
    })->name('admin.register');

    Route::get('/register/{roleType}', [CustomRegisterController::class, 'create'])
        ->whereIn('roleType', ['admin', 'user'])
        ->name('custom.register.form');

    Route::post('/register', [CustomRegisterController::class, 'store'])
        ->name('register.store');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index')
        ->middleware('role:admin|user');

    Route::resource('products', ProductController::class)
        ->except(['index', 'show'])
        ->middleware('role:admin');

    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('categories.index')
        ->middleware('role:admin|user');

    Route::resource('categories', CategoryController::class)
        ->except(['index', 'show'])
        ->middleware('role:admin');

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
    });
});