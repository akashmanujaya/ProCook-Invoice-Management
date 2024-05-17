<?php

use App\Http\Controllers\Auth\API\UserController;
use App\Http\Controllers\Invoices\v100\API\InvoicesAPIController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

use function App\Helpers\getModules;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware(['api.key', 'throttle:api'])->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('/logout', [UserController::class, 'logout']);
        Route::get('/profile', [UserController::class, 'profile']);
        
        /* START - comment this section if you want to test the feature testing*/
        $modules = getModules();

        foreach ($modules as $module) {
            try {
                $moduleClassPath = "App\Http\Controllers\\" . $module['module'] . "\\" . $module['version'] . '\Module';
                $moduleObj = new $moduleClassPath();
                $version = $moduleObj->getVersionPath(); // using in the module/routes.php
                $filePath = $moduleObj->getFilePath();
                $filePath = str_replace('\\', '/', "..\\" . $filePath . "\\Routes\\API\\routes.php");

                if (file_exists(app_path($filePath))) {
                    require_once app_path($filePath);
                }

            } catch (\Exception $e) {
                Log::error($module['module'] . "\\" . $module['version'] . ": Routes loading error", ['error' => $e->getMessage()]);
                exit();
            }
        }
        /* END - comment this section if you want to test the feature testing */

        /* START - uncomment this section if you want to test the feature testing */
        // Route::prefix('invoices')->group(function () {
        //     Route::get('/', [InvoicesAPIController::class, 'index']); // List all invoices with filters
        //     Route::post('/', [InvoicesAPIController::class, 'store']); // Create a new invoice
        //     Route::post('/toggle-status/{invoiceNumber}', [InvoicesAPIController::class, 'toggleStatus']); // Create a new invoice
        //     Route::get('/{invoiceNumber}', [InvoicesAPIController::class, 'show']); // Show a single invoice
        //     Route::put('/{id}', [InvoicesAPIController::class, 'update']); // Update an existing invoice
        //     Route::delete('/{id}', [InvoicesAPIController::class, 'destroy']); // Delete an invoice
        // });
        /* END - uncomment this section if you want to test the feature testing */
        
    });
});