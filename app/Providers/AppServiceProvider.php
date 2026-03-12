<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
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
     * Check if application is running in production environment
     * Considers config app.env and proxy headers (Railway, Cloudflare)
     */
    private function isProduction(): bool
    {
        if (config('app.env') === 'production') {
            return true;
        }

        // Check for HTTPS indicator from reverse proxies (Railway, Cloudflare, etc)
        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            return true;
        }

        // Check native HTTPS
        return !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production or when behind a proxy with HTTPS
        if ($this->isProduction()) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        // Register Cloudinary Driver
        Storage::extend('cloudinary', function ($config) {
            $cloudinaryAdapter = new CloudinaryAdapter($config);
            $filesystem = new Filesystem($cloudinaryAdapter);

            // Gunakan custom CloudinaryFilesystemAdapter
            return new CloudinaryFilesystemAdapter($filesystem, $cloudinaryAdapter, $config);
        });

        // View Composer Navbar
        View::composer('components.navbar', function ($view) {
            // Kita ambil Category -> Series -> Products (Beserta Gambar)
            $navbarCategories = Cache::remember('navbar_categories', 3600, function () {
                return Category::with(['series.products.images'])->get();
            });

            $view->with('navbarCategories', $navbarCategories);
        });
    }
}
