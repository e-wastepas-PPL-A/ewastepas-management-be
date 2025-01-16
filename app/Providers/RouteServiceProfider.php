<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;



class RouteServiceProfider extends ServiceProvider
{
    /**
     * The namespace for the controller routes.
     *
     * @var string
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
    public function map()
    {
        $this->mapApiRoutes();  // Memetakan rute API
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')  // Pastikan 'api' ada di URL
            ->middleware('api')  // Middleware untuk rute API
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));  // Memastikan rute diambil dari file ini
    }

}
