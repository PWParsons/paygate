<?php

namespace PWParsons\PayGate;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use PWParsons\PayGate\Foundation\Objects\JSONObject;

class PayGate
{
    private $config;
    private $data;
    public $resource;

    private $error_codes = [
        'CNTRY_INVALID'         => 'Invalid Country',
        'DATA_AMT_NUM'          => 'Amount is not a number',
        'DATA_AMT_ZERO'         => 'Amount value is zero',
        'DATA_CHK'              => 'Checksum calculated incorrectly',
        'DATA_CREF'             => 'No transaction reference',
        'DATA_DTTM'             => 'Transaction date invalid',
        'DATA_INS'              => 'Error creating record for transaction request',
        'DATA_PAY_REQ_ID'       => 'Pay request ID missing or invalid',
        'DATA_PM'               => 'Pay Method or Pay Method Detail fields invalid',
        'DATA_PW'               => 'Not all required fields have been posted to PayWeb',
        'DATA_REGION'           => 'No Country or Locale',
        'DATA_URL'              => 'No return url',
        'INVALID_VAULT'         => 'Vault value invalid',
        'INVALID_VAULT_ID'      => 'Vault ID invalid',
        'INV_EMAIL'             => 'Invalid Email address',
        'LOCALE_INVALID'        => 'Invalid Locale',
        'ND_INV_PGID'           => 'Invalid PayGate ID',
        'NOT_LIVE_PM'           => 'No available payment methods',
        'NO_TRANS_DATA'         => 'No transaction data found',
        'PAYVAULT_NOT_EN'       => 'PayVault not enabled',
        'PGID_NOT_EN'           => 'PayGate ID not enabled, no available payment methods or no available currencies',
        'TXN_CAN'               => 'Transaction has already been cancelled',
        'TXN_CMP'               => 'Transaction has already been completed',
        'TXN_PRC'               => 'Transaction is older than 30 minutes or there has been an error processing it',
        'VAULT_NOT_ACCEPTED'    => 'Card types enabled on terminal not available for vaulting',
    ];

    public function __construct(array $config)
    {
        $this->config = $this->validateConfig($config);
        $this->setDefaultData();
    }

    private function validateConfig(array $config)
    {
        foreach ($config as $key => $value) {
            if ($key == 'id' || $key == 'secret' || $key == 'return_url') {
                if (empty($value)) {
                    throw new \InvalidArgumentException('Please check you paygate configuration.');
                }
            }
        }

        return $config;
    }

    private function setDefaultData()
    {
        $this->data = [
            'PAYGATE_ID'        => $this->config['id'],
            'REFERENCE'         => '',
            'AMOUNT'            => '',
            'CURRENCY'          => $this->config['currency'],
            'RETURN_URL'        => $this->config['return_url'],
            'NOTIFY_URL'        => $this->config['notify_url'],
            'TRANSACTION_DATE'  => now()->format('Y-m-d H:i:s'),
            'LOCALE'            => $this->config['locale'],
            'COUNTRY'           => $this->config['country'],
            'EMAIL'             => ''
        ];
    }

    private function createRequest($body = [])
    {
        $httpRequest = new Client();

        try {
            $httpResponse = $httpRequest->post('https://secure.paygate.co.za/payweb3/initiate.trans', [
                'form_params' => $body
            ]);

            parse_str($httpResponse->getBody()->getContents(), $httpResponse);
        } catch (ClientException $e) {
            $httpResponse = $e->getResponse()->getBody()->getContents();
        } catch (RequestException $e) {
            $httpResponse = $e->getResponse()->getBody()->getContents();
        }

        return $httpResponse;
    }

    public function create(array $data)
    {
        $data['CHECKSUM'] = md5(implode('', $data) . $this->config['secret']);

        $response = $this->createRequest($data);

        $this->resource = new JSONObject($response, $this);

        return $this->resource;
    }

    public function instantiate()
    {
        $this->resource = new JSONObject($this->data, $this);
        
        return $this->resource;
    }
}
