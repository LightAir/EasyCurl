<?php

/**
 * Service Provider for Lumen Framework
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
}
