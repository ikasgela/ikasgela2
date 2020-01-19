<?php

namespace App\Providers;

use App\Http\View\Composers\ActividadesAsignadasComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class RecuentoActividadesProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', ActividadesAsignadasComposer::class);
    }
}
