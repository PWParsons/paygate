<?php

namespace PWParsons\PayGate\Foundation\Protocol;

use PWParsons\PayGate\Foundation\Objects\JSONObject;

class InitiateProtocol extends BaseProtocol
{
    protected string $endpoint = '/initiate.trans';

    public function instantiate(array $data = [], $protocol = false): JSONObject
    {
        return parent::instantiate([
            'data' => [
                'PAYGATE_ID' => config('paygate.id'),
                'REFERENCE' => '',
                'AMOUNT' => '',
                'CURRENCY' => config('paygate.currency'),
                'RETURN_URL' => config('paygate.return_url'),
                'TRANSACTION_DATE' => now()->format('Y-m-d H:i:s'),
                'LOCALE' => config('paygate.locale'),
                'COUNTRY' => config('paygate.country'),
                'EMAIL' => '',
                'NOTIFY_URL' => config('paygate.notify_url'),
            ],
        ], $this);
    }
}
