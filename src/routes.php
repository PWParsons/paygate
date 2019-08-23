<?php

use PWParsons\PayGate\Facades\PayGate;

Route::get('paygate', function () {
    $http = PayGate::instantiate()
                   ->withReference('Test')
                   ->withAmount(39.65)
                   ->withEmail('peterw.parsons@gmail.com')
                   ->create();

    dd($http);

    if ($result->succeeds()) {
        dd($result->all());
    } else {
        dump($result->getErrorCode());
        dump($result->getErrorMessage());
        die();
    }
});
