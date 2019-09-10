# PayGate

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

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

This will publish a file `paygate.php` in your config directory with the following contents:

```php
return [

    /**
     * Your PayGateID assigned by PayGate. If the ID is not provided,
     * then it will default to the testing ID.
     */
    'id'         => env('PAYGATE_ID', '10011072130'),

    /**
     * The encryption key set in the Merchant Access Portal. If the
     * encryption key is not provided, then it will default to the
     * testing key.
     */
    'secret'     => env('PAYGATE_SECRET', 'secret'),

    /**
     * Currency code of the currency the customer is paying in.
     * If the curency code is not provided, then it will default
     * to South Africa Rand.
     *
     * Refer to http://docs.paygate.co.za/#country-codes
     */
    'currency'   => env('PAYGATE_CURRENCY', 'ZAR'),

    /**
     * Country code of the country the customer is paying from.
     * If the country code is not provided, then it will default
     * to South Africa.
     *
     * Refer to http://docs.paygate.co.za/#country-codes
     */
    'country'    => env('PAYGATE_COUNTRY', 'ZAF'),

    /**
     * The locale code identifies to PayGate the customer’s
     * language. If the locale is not provided or supported,
     * then PayGate will default to the “en” locale.
     */
    'locale'     => env('PAYGATE_LOCALE', config('app.locale') ?: 'en'),

    /**
     * Once the transaction is completed, PayWeb will return
     * the customer to a page on your web site. The page the
     * customer must see is specified in this field. If the 
     * return url not not provided, then it will default to
     * your application's url.
     */
    'return_url' => env('PAYGATE_RETURN_URL', config('app.url')),

    /**
     * If the notify URL field is populated, then PayWeb will
     * post to the notify URL immediately when the transaction
     * is completed.
     *
     * Refer to http://docs.paygate.co.za/#response
     */
    'notify_url' => env('PAYGATE_NOTIFY_URL')

];
```

## Usage

After you've installed the package and filled in the values in the config-file working with this package will be a breeze. All the following examples use the facade. Don't forget to import it at the top of your file.

```php
use PayGate;
```

### Creating a transaction

The `with` and `get` magic methods that allows you to set or get a string after the word 'with' or 'get' provided within the object it is being called on.

```php
// Initiate transaction
$call = PayGate::initiate()
               ->withReference('pgtest_123456789')
               ->withAmount(32.99)
               ->withEmail('email@example.com')
               ->create();

if ($call->fails()) {
    $errorCode = $call->getErrorCode();
    $errorMsg  = $call->getErrorMessage();

    return "Dang it! Error '{$errorCode}' with message '{$errorMsg}'.";
}

// Redirect to PayGate's payment page
return PayGate::redirect();
```

If you need override the default values and add a notify url:

```php
$call = PayGate::initiate()
               ->withReference('pgtest_123456789')
               ->withAmount(32.99)
               ->withEmail('email@example.com')
               ->withCurrency('USD')
               ->withCountry('USA')
               ->withLocale('en-us')
               ->withReturnUrl('https://my.return.url/page')
               ->withNotifyUrl('https://my.notify.url/page')
               ->create();
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email [peterw.parsons@gmail.com](mailto:peterw.parsons@gmail.com) instead of using the issue tracker.

## Credits

- [author name][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/pwparsons/paygate.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/pwparsons/paygate.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/pwparsons/paygate/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/pwparsons/paygate
[link-downloads]: https://packagist.org/packages/pwparsons/paygate
[link-travis]: https://travis-ci.org/pwparsons/paygate
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/pwparsons
[link-contributors]: ../../contributors