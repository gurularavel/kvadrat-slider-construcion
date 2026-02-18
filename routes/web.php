<?php

use Illuminate\Support\Facades\Route;
use Msc\SliderConstructor\Http\Controllers\AdminController;
use Msc\SliderConstructor\Http\Controllers\FrontController;
use Msc\SliderConstructor\Http\Controllers\SliderApiController;
use Msc\SliderConstructor\Http\Controllers\UploadController;

$prefix      = config('slider-constructor.prefix', 'slider');
$middleware  = config('slider-constructor.middleware', ['web']);
$adminMw     = config('slider-constructor.admin_middleware', ['web']);

// Frontend
Route::middleware($middleware)
    ->prefix($prefix)
    ->name('slider-constructor.')
    ->group(function () {
        Route::get('/', [FrontController::class, 'index'])->name('front');
    });

// Admin
Route::middleware($adminMw)
    ->prefix($prefix . '/admin')
    ->name('slider-constructor.admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'editor'])->name('editor');

        // API routes — CSRF excluded
        Route::withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->group(function () {
                Route::any('api', [SliderApiController::class, 'dispatch'])->name('api');
                Route::post('upload', [UploadController::class, 'upload'])->name('upload');
            });
    });
