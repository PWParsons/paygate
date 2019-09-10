<?php

namespace PWParsons\PayGate\Foundation\Protocol;

class InitiateProtocol extends BaseProtocol
{
    /*
     * Extending endpoint of the BaseProtocol.
     *
     * @var string
     */
    protected $endpoint = '/initiate.trans';

    /*
     * Create new instance of an empty initiate object.
     *
     * @return mixed
     */
    public function instantiate($data = [], $protocol = false)
    {
        return parent::instantiate([
            'data' => [
                'PAYGATE_ID'        => config('paygate.id'),
                'REFERENCE'         => '',
                'AMOUNT'            => '',
                'CURRENCY'          => config('paygate.currency'),
                'RETURN_URL'        => config('paygate.return_url'),
                'NOTIFY_URL'        => config('paygate.notify_url'),
                'TRANSACTION_DATE'  => now()->format('Y-m-d H:i:s'),
                'LOCALE'            => config('paygate.locale'),
                'COUNTRY'           => config('paygate.country'),
                'EMAIL'             => '',
            ]
        ], $this);
    }
}
