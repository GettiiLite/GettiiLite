<?php
return [
    'url' => env('GETTII_URL'),
    'aid' => env('GETTII_AID'), // API利用コード
    'xcd' => env('GETTII_XCD'), // hash_hmac()のkey
    'basic_id' => env('GETTII_BASIC_ID'),
    'basic_pass' => env('GETTII_BASIC_PASS'),

    'privacypolicy_url' => 'https://www.gettii.jp/privacy/',
    'company_url'       => 'https://www.linkst.jp/'
];
