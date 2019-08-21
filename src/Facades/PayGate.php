<?php

namespace PWParsons\PayGate\Facades;

use Illuminate\Support\Facades\Facade;

class PayGate extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'paygate';
    }
}
