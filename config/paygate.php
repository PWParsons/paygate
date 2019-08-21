<?php

return [

    'id'         => env('PAYGATE_ID', '10011072130'),
    'secret'     => env('PAYGATE_SECRET', 'secret'),
    'currency'   => env('PAYGATE_CURRENCY', 'ZAR'),
    'country'    => env('PAYGATE_COUNTRY', 'ZAF'),
    'locale'     => env('PAYGATE_LOCALE', config('app.locale') ?: 'en-za'),
    'return_url' => env('PAYGATE_RETURN_URL'),
    'notify_url' => env('PAYGATE_NOTIFY_URL')

];
