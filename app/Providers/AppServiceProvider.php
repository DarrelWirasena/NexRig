<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       // View Composer Navbar
        View::composer('components.navbar', function ($view) {
            // Kita ambil Category -> Series -> Products (Beserta Gambar)
            // KITA HAPUS LIMIT 'take(3)' DISINI
            $navbarCategories = Category::with(['series.products.images'])->get();

            $view->with('navbarCategories', $navbarCategories);
        });
    }
}
