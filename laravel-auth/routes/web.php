<?php

use App\Http\Controllers\LegacyBridgeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/auth/login');

Route::middleware('auth')->get('/auth/legacy/bridge', [LegacyBridgeController::class, 'bridge'])->name('legacy.bridge');
Route::get('/auth/legacy/logout', [LegacyBridgeController::class, 'logout'])->name('legacy.logout');
