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

    public function toPayGate()
    {
        $url = $this->api->baseUrl . $this->endpoint;
        $request_id = session('PayGate.PAY_REQUEST_ID');
        $checksum = session('PayGate.CHECKSUM');

        session()->forget('PayGate');

        return view('PayGate::create', compact('url', 'request_id', 'checksum'));
    }
}
