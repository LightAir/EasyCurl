<?php

/**
 * Facade for Lumen Framework
 *
 * @package EasyCurl
 *
 * @author LightAir
 */

namespace LightAir\EasyCurl;

use Illuminate\Support\Facades\Facade;

class EasyCurlFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ecurl';
    }
}