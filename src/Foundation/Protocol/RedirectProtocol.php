<?php

namespace PWParsons\PayGate\Foundation\Protocol;

use Illuminate\View\View;

class RedirectProtocol extends BaseProtocol
{
    protected string $endpoint = '/process.trans';

    public function toPayGate(): View
    {
        $this->validateSession();

        $paygate = [
            'url' => $this->api->baseUrl.$this->endpoint,
            'request_id' => session('PAYGATE.PAY_REQUEST_ID'),
            'checksum' => session('PAYGATE.CHECKSUM'),
        ];

        session()->forget('PAYGATE');

        return view('PayGate::create', compact('paygate'));
    }

    private function validateSession()
    {
        if (! session()->has('PAYGATE')) {
            throw new \InvalidArgumentException('A transaction has not been inititated.');
        }
    }
}
