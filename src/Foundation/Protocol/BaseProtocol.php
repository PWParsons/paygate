<?php

namespace PWParsons\PayGate\Foundation\Protocol;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use PWParsons\PayGate\Foundation\Objects\JSONObject;
use PWParsons\PayGate\PayGate;

class BaseProtocol
{
    public PayGate $api;

    public JSONObject $resource;

    protected string $endpoint;

    protected array $errorCodes = [
        'CNTRY_INVALID' => 'Invalid Country',
        'DATA_AMT_NUM' => 'Amount is not a number',
        'DATA_AMT_ZERO' => 'Amount value is zero',
        'DATA_CHK' => 'Checksum calculated incorrectly',
        'DATA_CREF' => 'No transaction reference',
        'DATA_DTTM' => 'Transaction date invalid',
        'DATA_INS' => 'Error creating record for transaction request',
        'DATA_PAY_REQ_ID' => 'Pay request ID missing or invalid',
        'DATA_PM' => 'Pay Method or Pay Method Detail fields invalid',
        'DATA_PW' => 'Not all required fields have been posted to PayWeb',
        'DATA_REGION' => 'No Country or Locale',
        'DATA_URL' => 'No return url',
        'INVALID_VAULT' => 'Vault value invalid',
        'INVALID_VAULT_ID' => 'Vault ID invalid',
        'INV_EMAIL' => 'Invalid Email address',
        'LOCALE_INVALID' => 'Invalid Locale',
        'ND_INV_PGID' => 'Invalid PayGate ID',
        'NOT_LIVE_PM' => 'No available payment methods',
        'NO_TRANS_DATA' => 'No transaction data found',
        'PAYVAULT_NOT_EN' => 'PayVault not enabled',
        'PGID_NOT_EN' => 'PayGate ID not enabled, no available payment methods or no available currencies',
        'TXN_CAN' => 'Transaction has already been cancelled',
        'TXN_CMP' => 'Transaction has already been completed',
        'TXN_PRC' => 'Transaction is older than 30 minutes or there has been an error processing it',
        'VAULT_NOT_ACCEPTED' => 'Card types enabled on terminal not available for vaulting',
    ];

    protected array $transactionStatus = [
        '0' => 'Not Done',
        '1' => 'Approved',
        '2' => 'Declined',
        '3' => 'Cancelled',
        '4' => 'User Cancelled',
        '5' => 'Received by PayGate',
        '7' => 'Settlement Voided',
    ];

    protected array $paymentMethodCodes = [
        'CC' => 'Credit Card',
        'DC' => 'Debit Card',
        'EW' => 'E-Wallet',
        'BT' => 'Bank Transfer',
        'CV' => 'Cash Voucher',
        'PC' => 'Pre-Paid Card',
    ];

    public function __construct(PayGate $api)
    {
        $this->api = $api;
    }

    public function createRequest($body = [])
    {
        $request = new Client();

        try {
            $result = $request->post("{$this->api->baseUrl}{$this->endpoint}", [
                'form_params' => $body,
            ]);

            parse_str($result->getBody()->getContents(), $response);
        } catch (ClientException $e) {
            $response = $e->getResponse()->getBody()->getContents();
        } catch (RequestException $e) {
            $response = $e->getResponse()->getBody()->getContents();
        }

        return $response;
    }

    public function instantiate(array $data = [], $protocol = false): JSONObject
    {
        $this->resource = new JSONObject($data, $this);

        return $this->resource;
    }

    public function create(array $data): JSONObject
    {
        $data['data']['CHECKSUM'] = md5(implode('', $data['data']).config('paygate.secret'));

        $response = $this->createRequest($data['data']);

        if (array_key_exists('ERROR', $response)) {
            $response = [
                'ERROR_CODE' => $response['ERROR'],
                'ERROR_MESSAGE' => $this->errorCodes[$response['ERROR']],
            ];
        }

        session(['PAYGATE' => $response]);
        $this->resource->resource['meta'] = $response;

        return $this->resource;
    }
}
