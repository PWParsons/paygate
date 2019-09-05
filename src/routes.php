<?php

use PWParsons\PayGate\Facades\PayGate;

Route::get('paygate', function () {
    $http = PayGate::instantiate()
                   ->withReference('Test')
                   ->withAmount(39.65)
                   ->withEmail('peterw.parsons@gmail.com')
                   ->create();

    dd($http);

    if ($http->succeeds()) {
        dd($http->all());
    } else {
        dump($http->getErrorCode());
        // dump($http->getErrorMessage());
        die();
    }
});
