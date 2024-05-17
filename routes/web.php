<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use function App\Helpers\getModules;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Get all the modules
    $modules = getModules();

    foreach ($modules as $module) {
        try {
            $moduleClassPath = "App\Http\Controllers\\" . $module['module'] . "\\" . $module['version'] . '\Module';
            $moduleObj = new $moduleClassPath();
            $version = $moduleObj->getVersionPath(); // using in the module/routes.php
            $filePath = $moduleObj->getFilePath();
            $filePath = str_replace('\\', '/', "..\\" . $filePath . "\\Routes\\routes.php");


            if (file_exists(app_path($filePath))) {
                require_once app_path($filePath);
            }

        } catch (\Exception $e) {
            Log::error($module['module'] . "\\" . $module['version'] . ": Routes loading error", ['error' => $e->getMessage()]);
            exit();
        }
    }
});

require __DIR__.'/auth.php';
