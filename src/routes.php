<?php

use PWParsons\PayGate\Facades\PayGate;

Route::get('/', function () {
    $http = PayGate::initiate()
                   ->withReference('Test')
                   ->withAmount(39.65)
                   ->withEmail('peterw.parsons@gmail.com')
                   ->create();

    if ($http->fails()) {
        dump($http->getErrorCode());
        dump($http->getErrorMessage());
        die();
    }

    dd($http->getPayRequestId());
    dd($http->getChecksum());
    dd($http->all());

    return PayGate::redirect();
});
