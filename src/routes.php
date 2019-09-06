<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use PWParsons\PayGate\Facades\PayGate;

Route::get('paygate', function () {
    $http = PayGate::initiate()
                   ->instantiate()
                   ->withReference('Test')
                   ->withAmount(39.65)
                   ->withEmail('peterw.parsons@gmail.com')
                   ->create();
    
    if ($http->succeeds()) {
        return PayGate::redirect();

        dd($http->all());
    } else {
        dump($http->getErrorCode());
        dump($http->getErrorMessage());
        die();
    }
});
