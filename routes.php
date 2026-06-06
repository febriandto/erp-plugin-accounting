<?php

use Illuminate\Support\Facades\Route;
use Plugins\accounting\Controllers\InvoiceController;

Route::prefix('accounting')->name('accounting.')->group(function () {

    Route::middleware('can:accounting.view')->group(function () {
        Route::get('invoices',              [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('invoices/{invoice}',    [InvoiceController::class, 'show'])->name('invoices.show');
    });

    Route::middleware('can:accounting.manage')->group(function () {
        Route::get('invoices/create',              [InvoiceController::class, 'create'])->name('invoices.create');
        Route::post('invoices',                    [InvoiceController::class, 'store'])->name('invoices.store');
        Route::get('invoices/{invoice}/edit',      [InvoiceController::class, 'edit'])->name('invoices.edit');
        Route::put('invoices/{invoice}',           [InvoiceController::class, 'update'])->name('invoices.update');
        Route::delete('invoices/{invoice}',        [InvoiceController::class, 'destroy'])->name('invoices.destroy');
        Route::patch('invoices/{invoice}/status',  [InvoiceController::class, 'updateStatus'])->name('invoices.update-status');
    });

});
