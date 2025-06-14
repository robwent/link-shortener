<?php

use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Redirect to admin panel if configured
    if (config('shortener.homepage.redirect_to_admin')) {
        return redirect('/admin');
    }

    // Custom redirect URL takes precedence
    if ($customUrl = config('shortener.homepage.redirect_url')) {
        return redirect($customUrl);
    }

    // Use custom view or default welcome page
    $view = config('shortener.homepage.view', 'welcome');

    return view($view);
});

// QR Code routes (must come before the redirect route)
Route::get('/qr/{link}/download', [QrCodeController::class, 'generate'])
    ->name('qr.download')
    ->middleware('auth');

Route::get('/qr/{link}/display', [QrCodeController::class, 'display'])
        ->name('qr.display')
        ->middleware('auth');

// Redirect routes with rate limiting (must be last)
Route::get('/{shortCode}', [RedirectController::class, 'redirect'])
    ->name('redirect')
    ->middleware('throttle:60,1')
    ->where('shortCode', '[a-zA-Z0-9\-_]+');

Route::post('/{shortCode}', [RedirectController::class, 'redirect'])
    ->name('redirect.post')
    ->middleware('throttle:60,1')
    ->where('shortCode', '[a-zA-Z0-9\-_]+');
