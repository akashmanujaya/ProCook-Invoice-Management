<?php

use App\Http\Controllers\Auth\API\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Http\Request;
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
        
        // Get all the modules
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
    });
});