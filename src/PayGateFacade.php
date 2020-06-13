<?php

namespace PWParsons\PayGate;

use Illuminate\Support\Facades\Facade;

/**
 * @see \PWParsons\PayGate\PayGate
 */
class PayGateFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'paygate';
    }
}
