<?php

use App\Http\Controllers\DonationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DonationController::class, 'index'])->name('donations.index');

Route::post('/donasi', [DonationController::class, 'store'])->name('donations.store');

Route::post('/midtrans/notification', [DonationController::class, 'notification'])
    ->name('midtrans.notification');

Route::get('/donasi/success', [DonationController::class, 'success'])
    ->name('donations.success');

Route::get('/donasi/pending', [DonationController::class, 'pending'])
    ->name('donations.pending');

Route::get('/donasi/failed', [DonationController::class, 'failed'])
    ->name('donations.failed');

Route::get('/cek-midtrans', function () {
    return [
        'is_production' => config('midtrans.is_production'),
        'client_key' => config('midtrans.client_key'),
        'server_key_prefix' => substr(config('midtrans.server_key'), 0, 13),
    ];
});