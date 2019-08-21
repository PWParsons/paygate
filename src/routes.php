<?php

use PWParsons\PayGate\Facades\PayGate;

Route::get('paygate', function () {
    $result = PayGate::withReference('Test')
                     ->withAmount(39.65)
                     ->withEmail('peterw.parsons@gmail.com')
                     ->init();

    dd($result);
});
