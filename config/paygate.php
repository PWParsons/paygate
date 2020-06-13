<?php

return [

    /*
     * Your PayGateID assigned by PayGate. If the ID is not provided,
     * then it will default to the testing ID.
     */
    'id' => env('PAYGATE_ID', '10011072130'),

    /*
     * The encryption key set in the Merchant Access Portal. If the
     * encryption key is not provided, then it will default to the
     * testing key.
     */
    'secret' => env('PAYGATE_SECRET', 'secret'),

    /*
     * Currency code of the currency the customer is paying in.
     * If the curency code is not provided, then it will default
     * to South Africa Rand.
     *
     * Refer to http://docs.paygate.co.za/#country-codes
     */
    'currency' => env('PAYGATE_CURRENCY', 'ZAR'),

    /*
     * Country code of the country the customer is paying from.
     * If the country code is not provided, then it will default
     * to South Africa.
     *
     * Refer to http://docs.paygate.co.za/#country-codes
     */
    'country' => env('PAYGATE_COUNTRY', 'ZAF'),

    /*
     * The locale code identifies to PayGate the customer’s
     * language. If the locale is not provided or supported,
     * then PayGate will default to the “en” locale.
     */
    'locale' => env('PAYGATE_LOCALE', config('app.locale') ?: 'en'),

    /*
     * Once the transaction is completed, PayWeb will return
     * the customer to a page on your web site. The page the
     * customer must see is specified in this field.
     */
    'return_url' => env('PAYGATE_RETURN_URL', config('app.url')),

    /*
     * If the notify URL field is populated, then PayWeb will
     * post to the notify URL immediately when the transaction
     * is completed.
     *
     * Refer to http://docs.paygate.co.za/#response
     */
    'notify_url' => env('PAYGATE_NOTIFY_URL'),
];
