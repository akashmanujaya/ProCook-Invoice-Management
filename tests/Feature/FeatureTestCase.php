<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

use function App\Helpers\getModules;

abstract class FeatureTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Refresh the database
        $this->refreshDatabase();

        // Load dynamic routes
        $this->loadDynamicRoutes();
    }

    protected function loadDynamicRoutes()
    {
        // Get all the modules
        $modules = getModules();

        foreach ($modules as $module) {
            try {
                $moduleClassPath = "App\Http\Controllers\\" . $module['module'] . "\\" . $module['version'] . '\Module';
                $moduleObj = new $moduleClassPath();
                $version = $moduleObj->getVersionPath(); // using in the module/routes.php
                $filePath = $moduleObj->getFilePath();
                $filePath = str_replace('\\', '/', "../" . $filePath . "/Routes/API/routes.php");

                if (file_exists(app_path($filePath))) {
                    require_once app_path($filePath);
                }

            } catch (\Exception $e) {
                Log::error($module['module'] . "\\" . $module['version'] . ": Routes loading error", ['error' => $e->getMessage()]);
                exit();
            }
        }
    }
}
