# A Laravel package to integrate PayGate's PayWeb3 API

[![Latest Version](https://img.shields.io/github/release/pwparsons/paygate.svg?style=flat-square)](https://github.com/pwparsons/paygate/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![StyleCI](https://styleci.io/repos/203629326/shield?branch=master)](https://styleci.io/repos/203629326)
[![Total Downloads](https://img.shields.io/packagist/dt/pwparsons/paygate.svg?style=flat-square)](https://packagist.org/pwparsons/paygate)

This package provides an easy way to integrate PayGate's PayWeb3 API with Laravel 5.

The official documentation can be found [here](http://docs.paygate.co.za/#payweb-3)

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

After you've installed the package and filled in the values in the config file working with this package will be a breeze. All the following examples use the facade. Don't forget to import it at the top of your file.

```php
use PayGate;
```

### Creating a transaction

```php
// Initiate transaction
$http = PayGate::initiate()
               ->withReference('pgtest_123456789')
               ->withAmount(32.99)
               ->withEmail('email@example.com')
               ->create();

if ($http->fails()) {
    $errorCode = $http->getErrorCode();
    $errorMsg  = $http->getErrorMessage();

    return "Dang it! Error '{$errorCode}' with message '{$errorMsg}'.";
}

// Redirect to PayGate's payment page
return PayGate::redirect();
```

If you need override the default values and add a notify url:

```php
$http = PayGate::initiate()
               ->withReference('pgtest_123456789')
               ->withAmount(32.99)
               ->withEmail('email@example.com')
               ->withCurrency('USD')
               ->withCountry('USA')
               ->withLocale('en-us')
               ->withReturnUrl('https://my.return.url/page')
               ->withNotifyUrl('https://my.notify.url/page')
               ->create();

dd($http->all());
```

An example of the initiate response can be found in the [documentation](http://docs.paygate.co.za/#response).

### Querying a transaction

```php
$http = PayGate::query()
               ->withPayRequestId('YOUR_PAY_REQUEST_ID_HERE')
               ->withReference('pgtest_123456789')
               ->create();

if ($http->fails()) {
    $errorCode = $http->getErrorCode();
    $errorMsg  = $http->getErrorMessage();

    return "Dang it! Error '{$errorCode}' with message '{$errorMsg}'.";
}

dd($http->all());
```

An example of the query response can be found in the [documentation](http://docs.paygate.co.za/#response-2).

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

```php
echo $object->getPaygateId();       // 10011072130
echo $object->getPayRequestId();    // 23B785AE-C96C-32AF-4879-D2C9363DB6E8
echo $object->getReference();       // pgtest_123456789
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email [peterw.parsons@gmail.com](mailto:peterw.parsons@gmail.com) instead of using the issue tracker.

## Credits

- [Peter Parsons][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see the [license file](license.md) for more information.

[link-author]: https://github.com/pwparsons
[link-contributors]: ../../contributors