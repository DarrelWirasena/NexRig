<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use App\Filesystem\CloudinaryAdapter;
use App\Filesystem\CloudinaryFilesystemAdapter;
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
        // Register Cloudinary Driver
        Storage::extend('cloudinary', function ($app, $config) {
            $cloudinaryAdapter = new CloudinaryAdapter($config);
            $filesystem = new Filesystem($cloudinaryAdapter);
            
            // Gunakan custom CloudinaryFilesystemAdapter
            return new CloudinaryFilesystemAdapter($filesystem, $cloudinaryAdapter, $config);
        });

        // View Composer Navbar
        View::composer('components.navbar', function ($view) {
            // Kita ambil Category -> Series -> Products (Beserta Gambar)
            $navbarCategories = Category::with(['series.products.images'])->get();

            $view->with('navbarCategories', $navbarCategories);
        });
    }
}