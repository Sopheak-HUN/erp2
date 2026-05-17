<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Health check
    Route::get('/health', fn() => response()->json([
        'status' => 'ok',
        'app' => config('app.name'),
        'timestamp' => now()->toIso8601String(),
    ]));

    // Auth routes (public)
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
    });

    // Protected routes
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Module routes will be registered here
        // Route::apiResource('accounts', Accounting\AccountController::class);
        // Route::apiResource('journal-entries', Accounting\JournalEntryController::class);
        // Route::apiResource('invoices', Tax\InvoiceController::class);
        // Route::apiResource('employees', Payroll\EmployeeController::class);
        // Route::apiResource('products', Inventory\ProductController::class);
    });
});
