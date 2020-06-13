<?php

namespace PWParsons\PayGate\Foundation\Protocol;

class QueryProtocol extends BaseProtocol
{
    /*
     * Extending endpoint of the BaseProtocol.
     *
     * @var string
     */
    protected $endpoint = '/query.trans';

    /*
     * Create new instance of an empty initiate object.
     *
     * @return mixed
     */
    public function instantiate($data = [], $protocol = false)
    {
        return parent::instantiate([
            'data' => [
                'PAYGATE_ID' => config('paygate.id'),
                'PAY_REQUEST_ID' => '',
                'REFERENCE' => '',
            ],
        ], $this);
    }

    /*
     * Special primary function to get the transaction status
     * specifically for the query intent type.
     *
     * @return string
     */
    public function transactionStatus()
    {
        return $this->transactionStatus[$this->resource->resource['meta']['TRANSACTION_STATUS']];
    }

    /*
     * Special primary function to get the payment method
     * specifically for the query intent type.
     *
     * @return string
     */
    public function paymentMethod()
    {
        return $this->paymentMethodCodes[$this->resource->resource['meta']['PAY_METHOD']];
    }
}
