<?php

use App\Http\Controllers\Api\ApiCheckController;
use App\Http\Controllers\Api\TokenController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('/public/check', [ApiCheckController::class, 'public']);
    Route::post('/auth/token', [TokenController::class, 'store'])->middleware('throttle:5,1');
    Route::get('/private/check', [ApiCheckController::class, 'private'])->middleware(['auth:sanctum', 'abilities:private:check']);
});
