<?php

namespace PWParsons\PayGate\Foundation\Protocol;

class RedirectProtocol extends BaseProtocol
{
    protected string $endpoint = '/process.trans';

    public function toPayGate(): string
    {
        $this->validateSession();

        $paygate = [
            'url' => $this->api->baseUrl.$this->endpoint,
            'request_id' => session('PAYGATE.PAY_REQUEST_ID'),
            'checksum' => session('PAYGATE.CHECKSUM'),
        ];

        session()->forget('PAYGATE');

        return <<<HTML
            <form action="{$paygate['url']}" method="POST" name="paygate-form">
                <input type="hidden" name="PAY_REQUEST_ID" value="{$paygate['request_id']}">
                <input type="hidden" name="CHECKSUM" value="{$paygate['checksum']}">
            </form>

            <script>
                window.onload = () => document.forms['paygate-form'].submit();
            </script>
        HTML;
    }

    private function validateSession()
    {
        if (! session()->has('PAYGATE')) {
            throw new \InvalidArgumentException('A transaction has not been inititated.');
        }
    }
}
