<?php

namespace PWParsons\PayGate;

use PWParsons\PayGate\Foundation\Protocol\InitiateProtocol;
use PWParsons\PayGate\Foundation\Protocol\RedirectProtocol;

class PayGate
{
    /**
     * The config container.
     *
     * @var Config
     */
    public $config;

    /**
     * The base URL for API calls.
     *
     * @var JSONObject
     */
    public $baseUrl = 'https://secure.paygate.co.za/payweb3';

    /**
     * The initiate protocol container.
     *
     * @var InitiateProtocol
     */
    private $initiate;

    /**
     * The redirect protocol container.
     *
     * @var RedirectProtocol
     */
    private $redirect;

    /**
     * Basically a bootstrapper for the API class,
     * ensures config integrity and throws an exception
     * if there are issues with the config.
     *
     * @return void
     *
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->validateConfig();

        $this->initiate = new InitiateProtocol($this);
        $this->redirect = new RedirectProtocol($this);
    }

    /**
     * Pre-allocates the authentication header to be submitted
     * with each API request.
     *
     * @return null
     *
     * @throws Exception If the configuration file is missing required values.
     *
     */
    private function validateConfig()
    {
        foreach ($this->config as $key => $value) {
            if ($key == 'id' || $key == 'secret' || $key == 'return_url') {
                if (empty($value)) {
                    throw new \InvalidArgumentException('Please check you paygate configuration.');
                }
            }
        }
    }

    /**
     * Returns the initiate protocol container
     *
     * @return InitiateProtocol
     *
     */
    public function initiate()
    {
        return $this->initiate;
    }

    /**
     * Returns the redirect protocol container
     *
     * @return RedirectProtocol
     *
     */
    public function redirect()
    {
        return $this->redirect->toPayGate();
    }
}
