<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SnowflakeFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'snowflake'; }
}
