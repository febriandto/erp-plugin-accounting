<?php

use Illuminate\Support\Facades\Route;
use Plugins\accounting\Controllers\InvoiceController;
use Plugins\accounting\Controllers\ExpenseController;

Route::prefix('accounting')->name('accounting.')->group(function () {
    // Invoices — static before dynamic
    Route::get('invoices',                    [InvoiceController::class, 'index'])->name('invoices.index')->middleware('can:accounting.view');
    Route::get('invoices/create',             [InvoiceController::class, 'create'])->name('invoices.create')->middleware('can:accounting.manage');
    Route::post('invoices',                   [InvoiceController::class, 'store'])->name('invoices.store')->middleware('can:accounting.manage');
    Route::get('invoices/{invoice}',          [InvoiceController::class, 'show'])->name('invoices.show')->middleware('can:accounting.view');
    Route::get('invoices/{invoice}/edit',     [InvoiceController::class, 'edit'])->name('invoices.edit')->middleware('can:accounting.manage');
    Route::put('invoices/{invoice}',          [InvoiceController::class, 'update'])->name('invoices.update')->middleware('can:accounting.manage');
    Route::delete('invoices/{invoice}',       [InvoiceController::class, 'destroy'])->name('invoices.destroy')->middleware('can:accounting.manage');
    Route::patch('invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.update-status')->middleware('can:accounting.manage');

    // Expenses — static before dynamic
    Route::get('expenses',                     [ExpenseController::class, 'index'])->name('expenses.index')->middleware('can:accounting.view');
    Route::get('expenses/create',              [ExpenseController::class, 'create'])->name('expenses.create')->middleware('can:accounting.manage');
    Route::post('expenses',                    [ExpenseController::class, 'store'])->name('expenses.store')->middleware('can:accounting.manage');
    Route::get('expenses/{expense}',           [ExpenseController::class, 'show'])->name('expenses.show')->middleware('can:accounting.view');
    Route::get('expenses/{expense}/edit',      [ExpenseController::class, 'edit'])->name('expenses.edit')->middleware('can:accounting.manage');
    Route::put('expenses/{expense}',           [ExpenseController::class, 'update'])->name('expenses.update')->middleware('can:accounting.manage');
    Route::delete('expenses/{expense}',        [ExpenseController::class, 'destroy'])->name('expenses.destroy')->middleware('can:accounting.manage');
    Route::patch('expenses/{expense}/status',  [ExpenseController::class, 'updateStatus'])->name('expenses.update-status')->middleware('can:accounting.manage');
});
