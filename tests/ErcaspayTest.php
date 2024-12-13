<?php

use Decodeblock\Ercaspay\Ercaspay;
use Decodeblock\Ercaspay\Exceptions\IsNullException;

beforeEach(function () {
    $this->ercaspay = new Ercaspay;
    $this->callPrivateMethod($this->ercaspay, 'initializeClient', false);
});

it('generates a payment reference in UUID format', function () {
    $paymentRef = $this->ercaspay->generatePaymentReferenceUuid();
    expect($paymentRef)
        ->toBeString()
        ->toMatch('/[0-9a-fA-F\-]{36}/'); // Matches UUID format
});

