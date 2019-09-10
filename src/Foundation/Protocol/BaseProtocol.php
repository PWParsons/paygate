<?php

namespace PWParsons\PayGate\Foundation\Protocol;

use GuzzleHttp\Client;
use PWParsons\PayGate\PayGate;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use PWParsons\PayGate\Foundation\Objects\JSONObject;

class BaseProtocol
{
    /**
     * API Object class container.
     *
     * @var PayGate
     */
    public $api;

    /**
     * The resource container.
     *
     * @var JSONObject
     */
    public $resource;

    /**
     * Parent endpoint of the BaseProtocol.
     *
     * @var string
     */
    protected $endpoint;

    /**
     * Error codes.
     *
     * @var array
     */
    protected $errorCodes = [
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

    /**
     * Construct the base protocol class.
     *
     * @param  PayGate $api
     *
     * @return void
     */
    public function __construct(PayGate $api)
    {
        $this->api = $api;
    }

    /**
     * Create HTTP request wrapped around GuzzleHttp client and return result.
     *
     * @return JSONObject
     */
    public function createRequest($body = [])
    {
        $request = new Client();

        try {
            $response = $request->post("{$this->api->baseUrl}{$this->endpoint}", [
                'form_params' => $body,
            ]);

            parse_str($response->getBody()->getContents(), $response);
        } catch (ClientException $e) {
            $response = $e->getResponse()->getBody()->getContents();
        } catch (RequestException $e) {
            $response = $e->getResponse()->getBody()->getContents();
        }

        return $response;
    }

    /**
     * Create new instance of object.
     *
     * @return JSONObject
     */
    public function instantiate($data = [], $protocol = false)
    {
        $this->resource = new JSONObject($data, $this);

        return $this->resource;
    }

    /**
     * Submit request to API to store an object.
     *
     * @return JSONObject
     */
    public function create(array $data)
    {
        $data['data']['CHECKSUM'] = md5(implode('', $data['data']).config('paygate.secret'));

        $response = $this->createRequest($data['data']);

        if (array_key_exists('ERROR', $response)) {
            $response = [
                'ERROR_CODE'    => $response['ERROR'],
                'ERROR_MESSAGE' => $this->errorCodes[$response['ERROR']],
            ];
        }

        session(['PAYGATE' => $response]);
        $this->resource->resource['meta'] = $response;

        return $this->resource;
    }
}
