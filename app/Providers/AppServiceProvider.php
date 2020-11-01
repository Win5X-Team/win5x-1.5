<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Build;
use MatthiasMullie\Minify\CSS;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        view()->share('version', Build::get());
        view()->share('production', Build::isProduction());

        /** Replaces asset path with $prefix/dist (obfuscated data) if current build is tagged as release
          * and appends version at the end to reset user cache */
        $asset = function($path, $prefix) {
            if(Build::isProduction()) $prefix = $prefix.'/dist';
            return asset($prefix.$path.'?v='.Build::get());
        };
        /** Minifies output css file if current build is tagged as debug and $path doesn't exists,
         *  then appends version at the end to reset user cache */
        $css = function($path) {
            $filename = substr($path, strrpos($path, '/') + 1);
            $filepath = substr($path, 0, strrpos($path, '/'));
            if(!Build::isProduction() && !file_exists(public_path('/css/dist/'.$filename))) {
                $minifier = new CSS(public_path($path));
                $minifier->minify((str_contains("win5x", url('/')) ? '/var/www/html/' : 'D:/xampp/htdocs/') . '/public'.$filepath.'/dist/'.$filename);
            }
            return asset($filepath.(Build::isProduction() ? '/dist/' : '/').$filename.'?v='.Build::get());
        };

        view()->share('asset', $asset);
        view()->share('css', $css);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
