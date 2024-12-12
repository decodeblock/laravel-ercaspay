<?php

// config for Decodeblock/Ercaspay
return [
    'secretKey' => env('ERCASPAY_SECRET_KEY', 'ECRS-TEST-SECRET'),
    'baseUrl' => env('ERCASPAY_BASE_URL', 'https://gw.ercaspay.com'),
];
