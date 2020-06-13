<?php

namespace PWParsons\PayGate\Foundation\Protocol;

use PWParsons\PayGate\Foundation\Objects\JSONObject;

class QueryProtocol extends BaseProtocol
{
    protected string $endpoint = '/query.trans';

    public function instantiate(array $data = [], $protocol = false): JSONObject
    {
        return parent::instantiate([
            'data' => [
                'PAYGATE_ID' => config('paygate.id'),
                'PAY_REQUEST_ID' => '',
                'REFERENCE' => '',
            ],
        ], $this);
    }

    public function transactionStatus(): string
    {
        return $this->transactionStatus[$this->resource->resource['meta']['TRANSACTION_STATUS']];
    }

    public function paymentMethod(): string
    {
        return $this->paymentMethodCodes[$this->resource->resource['meta']['PAY_METHOD']];
    }
}
