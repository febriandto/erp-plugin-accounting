<?php

use Illuminate\Support\Facades\Route;
use Plugins\accounting\Controllers\InvoiceController;

Route::prefix('accounting')->name('accounting.')->group(function () {
    // Static routes dulu, baru dynamic — cegah 'create' tercocok sebagai {invoice}
    Route::get('invoices',                    [InvoiceController::class, 'index'])->name('invoices.index')->middleware('can:accounting.view');
    Route::get('invoices/create',             [InvoiceController::class, 'create'])->name('invoices.create')->middleware('can:accounting.manage');
    Route::post('invoices',                   [InvoiceController::class, 'store'])->name('invoices.store')->middleware('can:accounting.manage');
    Route::get('invoices/{invoice}',          [InvoiceController::class, 'show'])->name('invoices.show')->middleware('can:accounting.view');
    Route::get('invoices/{invoice}/edit',     [InvoiceController::class, 'edit'])->name('invoices.edit')->middleware('can:accounting.manage');
    Route::put('invoices/{invoice}',          [InvoiceController::class, 'update'])->name('invoices.update')->middleware('can:accounting.manage');
    Route::delete('invoices/{invoice}',       [InvoiceController::class, 'destroy'])->name('invoices.destroy')->middleware('can:accounting.manage');
    Route::patch('invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.update-status')->middleware('can:accounting.manage');
});
