# WARNING: This repository is no longer maintained :warning:

> This repository will not be updated. The repository will be kept available in read-only mode.

<img src="https://repository-images.githubusercontent.com/203629326/d9fb0480-add4-11ea-91ec-99a6638a9496">

<br><br>

[![Latest Version](https://img.shields.io/github/release/pwparsons/paygate.svg)](https://github.com/pwparsons/paygate/releases)
![Tests](https://github.com/PWParsons/paygate/workflows/Tests/badge.svg)
![Psalm](https://github.com/PWParsons/paygate/workflows/Psalm/badge.svg)
![Code Style](https://github.com/PWParsons/paygate/workflows/Code%20Style/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/pwparsons/paygate.svg)](https://packagist.org/pwparsons/paygate)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)

This package provides an easy way to integrate PayGate's PayWeb3 API with Laravel.

The official documentation can be found [here](http://docs.paygate.co.za/#payweb-3).

 Compatibility Chart
--------------------------------------------------------------------------------

| Package Version | Laravel      | PHP  |
|-----------------|--------------|------|
|    **2.0.0**    | 7.15+        | 7.4+ |
|      1.3.2      | 5.6 – 6.x    | 7.1+ |

## Installation

You can install this package via composer using:

```bash
composer require pwparsons/paygate
```

The package will automatically register itself.

To publish the config file to `config/paygate.php` run:

```bash
php artisan vendor:publish --tag=paygate.config
```

## Usage

After you've installed the package and filled in the values in the config file working with this package will be a breeze. All the following examples use the facade.

### Creating a transaction

```php
// Initiate transaction
$http = PayGate::initiate()
               ->withReference('pgtest_123456789')
               ->withAmount(32.99)
               ->withEmail('email@example.com')
               ->withCurrency('USD') // Optional: defaults to ZAR
               ->withCountry('USA') // Optional: defaults to ZAF
               ->withLocale('en-us') // Optional: defaults to 'en'
               ->withReturnUrl('https://website.com/return_url')
               ->withNotifyUrl('https://website.com/notify_url') // Optional
               ->create();

if ($http->fails()) {
    dump($http->getErrorCode());
    dump($http->getErrorMessage());
    dump($http->all());
}

// Redirect to PayGate's payment page
return PayGate::redirect();
```

An example of the initiate response can be found in the [documentation](http://docs.paygate.co.za/#response).

### Querying a transaction

```php
$http = PayGate::query()
               ->withPayRequestId('YOUR_PAY_REQUEST_ID_HERE')
               ->withReference('pgtest_123456789')
               ->create();

if ($http->fails()) {
    dump($http->getErrorCode());
    dump($http->getErrorMessage());
    dump($http->all());
}

dd($http->all());
```

An example of the query response can be found in the [documentation](http://docs.paygate.co.za/#response-2).

### Tip

Paygate will post to the RETURN_URL and NOTIFY_URL. Exclude these URI's from CSRF verification by adding them to the VerifyCsrfToken middleware. E.g.

```php
class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'return_url',
        'notify_url',
    ];
}
```

### Helpful Methods

The `with` magic method allows you to set a string after the word 'with' provided within the object it is being called on. This works in exactly the same way as the magic getter except it sets field values and returns the object so that you can chain setters, for example:

```php
$object->withReference('pgtest_123456789')
       ->withAmount(32.99)
       ->withEmail('email@example.com')
       ->withReturnUrl('https://my.return.url/page');
```

Will result in the following:

```json
{
    "REFERENCE": "pgtest_123456789",
    "AMOUNT": "3299",
    "EMAIL": "email@example.com",
    "RETURN_URL": "https://my.return.url/page"
}
```

The `get` magic method allows you to call any string after the word 'get' and it will return that value, for example:

```json
{
    "PAYGATE_ID": "10011072130",
    "PAY_REQUEST_ID": "23B785AE-C96C-32AF-4879-D2C9363DB6E8",
    "REFERENCE": "pgtest_123456789"
}
```

Getting data from the object:

```php
echo $object->getPaygateId();       // 10011072130
echo $object->getPayRequestId();    // 23B785AE-C96C-32AF-4879-D2C9363DB6E8
echo $object->getReference();       // pgtest_123456789
```

## Change log

Please see the [changelog](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](CONTRIBUTING.md) for details and a todolist.

## Security

If you discover any security related issues, please email [peterw.parsons@gmail.com](mailto:peterw.parsons@gmail.com) instead of using the issue tracker.

## Credits

- [Peter Parsons](https://github.com/pwparsons)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see the [license file](LICENSE.md) for more information. 
