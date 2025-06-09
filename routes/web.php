<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\DailyStockCheckController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\FinancialEntryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Shop selection routes
    Route::get('/shops/select', [ShopController::class, 'select'])->name('shops.select');
    Route::post('/shops/set', [ShopController::class, 'set'])->name('shops.set');
    
    // Direct route for creating a shop - no extra middleware
    Route::get('/shops/create-first', function () {
        // Always allow creating the first shop
        session(['first_shop_creation' => true]);
        return view('shops.create-app');
    })->name('shops.create.first');
    
    // Shop resource routes
    Route::resource('shops', ShopController::class);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Add shop.selected middleware to all routes that require a shop to be selected
    Route::middleware(['shop.selected'])->group(function () {
        // Product routes
        Route::resource('products', ProductController::class);
        
        // Stock-In routes
        Route::resource('stock-ins', StockInController::class);
        
        // Daily Stock Check routes
        Route::resource('daily-stock-checks', DailyStockCheckController::class);
        Route::get('/daily-stock-checks/create/{type}', [DailyStockCheckController::class, 'createByType'])
            ->name('daily-stock-checks.create.type');
        
        // Sales routes
        Route::resource('sales', SaleController::class);
        
        // Customer Payment routes
        Route::get('/customer-payments', [CustomerPaymentController::class, 'index'])->name('customer-payments.index');
        Route::get('/customer-payments/create/{sale}', [CustomerPaymentController::class, 'create'])->name('customer-payments.create');
        Route::post('/customer-payments', [CustomerPaymentController::class, 'store'])->name('customer-payments.store');
        
        // Financial Entry routes
        Route::resource('financial-entries', FinancialEntryController::class);
        
        // Reports routes
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
            Route::get('/discrepancy', [ReportController::class, 'discrepancy'])->name('discrepancy');
            Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
            Route::get('/customer-dues', [ReportController::class, 'customerDues'])->name('customer-dues');
            Route::get('/bag-weights', [ReportController::class, 'bagWeights'])->name('bag-weights');
            
            // Export routes
            Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');
        });
        
        // Export routes
        Route::prefix('export')->name('export.')->group(function () {
            Route::get('/stock', [ExportController::class, 'stockReport'])->name('stock');
            Route::get('/financial', [ExportController::class, 'financialReport'])->name('financial');
            Route::get('/customer-dues', [ExportController::class, 'customerDuesReport'])->name('customer-dues');
            Route::get('/pdf/{type}', [ExportController::class, 'exportPdf'])->name('pdf');
        });
    });
    
    // User & Role Management (Owner only)
    Route::middleware(['owner'])->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/invite', [UserController::class, 'invite'])->name('users.invite');
        Route::get('/users/{user}/roles', [UserController::class, 'roles'])->name('users.roles');
        Route::post('/users/{user}/roles', [UserController::class, 'updateRoles'])->name('users.roles.update');
    });
});

require __DIR__.'/auth.php';