<?php

namespace PWParsons\PayGate\Foundation\Protocol;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use PWParsons\PayGate\Foundation\Protocol\BaseProtocol;

class RedirectProtocol extends BaseProtocol
{
    /**
     * Extending endpoint of the BaseProtocol
     *
     * @var string
     */
    protected $endpoint = '/process.trans';

    /**
     * Return the view with the form that is submitted automatically
     *
     * @return \Illuminate\View\View
     */
    public function toPayGate()
    {
        $this->validateSession();

        $paygate = [
            'url'           => $this->api->baseUrl . $this->endpoint,
            'request_id'    => session('PAYGATE.PAY_REQUEST_ID'),
            'checksum'      => session('PAYGATE.CHECKSUM'),
        ];

        session()->forget('PAYGATE');

        return view('PayGate::create', compact('paygate'));
    }

    private function validateSession()
    {
        if (!session()->has('PAYGATE')) {
            throw new \InvalidArgumentException('A transaction has not been inititated.');
        }
    }
}
