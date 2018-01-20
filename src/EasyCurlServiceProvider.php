<?php

/**
 * Service Provider for Laravel & Lumen Frameworks
 *
 * @package EasyCurl
 *
 * @author LightAir
 */

namespace LightAir\EasyCurl;

use Illuminate\Support\ServiceProvider;

class EasyCurlServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ecurl', function () {

            return (new EasyCurl())
                ->setTimeOut($this->app['config']['ecurl.timeout'])
                ->setProxy($this->app['config']['ecurl.proxy'])
                ->setUserAgent($this->app['config']['ecurl.userAgent']);
        });
    }

    protected function setupConfig()
    {
        $source = dirname(__DIR__) . '/src/ecurl.php';

        if ($this->app instanceof LaravelApplication) {
            $this->publishes([$source => config_path('ecurl.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('ecurl');
        }
        $this->mergeConfigFrom($source, 'ecurl');
    }
}
