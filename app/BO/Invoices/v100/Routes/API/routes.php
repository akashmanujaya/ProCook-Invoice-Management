<?php

use App\Http\Controllers\Invoices\v100\API\InvoicesAPIController;
use Illuminate\Support\Facades\Route;


/**
 * API Routes for Invoice Management.
 *
 * These routes handle the API endpoints for managing invoices. They are prefixed with
 * 'api/invoices' and make use of the InvoicesAPIController to handle requests.
 * The routes are dynamically namespaced to support versioning.
 */
Route::prefix('invoices')->namespace('Invoices\\' . $version)->group(function () {
    Route::get('/', [InvoicesAPIController::class, 'index']); // List all invoices
    Route::post('/', [InvoicesAPIController::class, 'store']); // Create a new invoice
    Route::get('/{id}', [InvoicesAPIController::class, 'show']); // Show a single invoice
    Route::put('/{id}', [InvoicesAPIController::class, 'update']); // Update an existing invoice
    Route::delete('/{id}', [InvoicesAPIController::class, 'destroy']); // Delete an invoice
});
