<?php

namespace PWParsons\PayGate;

use PWParsons\PayGate\Foundation\Objects\JSONObject;
use PWParsons\PayGate\Foundation\Protocol\InitiateProtocol;
use PWParsons\PayGate\Foundation\Protocol\QueryProtocol;
use PWParsons\PayGate\Foundation\Protocol\RedirectProtocol;

class PayGate
{
    public array $config;

    public string $baseUrl = 'https://secure.paygate.co.za/payweb3';

    private InitiateProtocol $initiate;

    private RedirectProtocol $redirect;

    private QueryProtocol $query;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->validateConfig();

        $this->initiate = new InitiateProtocol($this);
        $this->redirect = new RedirectProtocol($this);
        $this->query = new QueryProtocol($this);
    }

    private function validateConfig(): void
    {
        foreach ($this->config as $key => $value) {
            if ($key == 'id' || $key == 'secret' || $key == 'return_url') {
                if (empty($value)) {
                    throw new \InvalidArgumentException('Please check you paygate configuration.');
                }
            }
        }
    }

    public function initiate(): JSONObject
    {
        return $this->initiate->instantiate();
    }

    public function redirect(): string
    {
        return $this->redirect->toPayGate();
    }

    public function query(): JSONObject
    {
        return $this->query->instantiate();
    }
}
