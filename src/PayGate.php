<?php

namespace PWParsons\PayGate;

use PWParsons\PayGate\Foundation\Protocol\QueryProtocol;
use PWParsons\PayGate\Foundation\Protocol\InitiateProtocol;
use PWParsons\PayGate\Foundation\Protocol\RedirectProtocol;

class PayGate
{
    /*
     * The config container.
     *
     * @var Config
     */
    public $config;

    /*
     * The base URL for API calls.
     *
     * @var JSONObject
     */
    public $baseUrl = 'https://secure.paygate.co.za/payweb3';

    /*
     * The initiate protocol container.
     *
     * @var InitiateProtocol
     */
    private $initiate;

    /*
     * The redirect protocol container.
     *
     * @var RedirectProtocol
     */
    private $redirect;

    /*
     * The query protocol container.
     *
     * @var QueryProtocol
     */
    private $query;

    /*
     * Basically a bootstrapper for the API class, ensures config integrity and
     * throws an exception if there are issues with the config.
     *
     * @return void
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->validateConfig();

        $this->initiate = new InitiateProtocol($this);
        $this->redirect = new RedirectProtocol($this);
        $this->query    = new QueryProtocol($this);
    }

    /*
     * Validates the required configuration settings.
     *
     * @return null
     *
     * @throws Exception If the configuration file is missing required values.
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

    /*
     * Returns the initiate json object.
     *
     * @return JSONObject
     */
    public function initiate()
    {
        return $this->initiate->instantiate();
    }

    /*
     * Returns the view that redirects to PayGate.
     *
     * @return \Illuminate\View\View
     */
    public function redirect()
    {
        return $this->redirect->toPayGate();
    }

    /*
     * Returns the query json object.
     *
     * @return JSONObject
     */
    public function query()
    {
        return $this->query->instantiate();
    }
}
