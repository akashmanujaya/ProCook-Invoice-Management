<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Invoices\v100\InvoicesController;

/**
 * API Routes for Invoice Management.
 *
 * These routes handle the API endpoints for managing invoices. They are prefixed with
 * 'api/invoices' and make use of the InvoicesController to handle requests.
 * The routes are dynamically namespaced to support versioning.
 */
Route::prefix('invoices')->namespace('Invoices\\' . $version)->group(function () {
    Route::get('/', [InvoicesController::class, 'index']); // List all invoices with filters
    Route::post('/', [InvoicesController::class, 'store']); // Create a new invoice
    Route::post('/toggle-status/{invoiceNumber}', [InvoicesController::class, 'toggleStatus']); // Create a new invoice
    Route::get('/{invoiceNumber}', [InvoicesController::class, 'show']); // Show a single invoice
    Route::put('/{id}', [InvoicesController::class, 'update']); // Update an existing invoice
    Route::delete('/{id}', [InvoicesController::class, 'destroy']); // Delete an invoice
});
