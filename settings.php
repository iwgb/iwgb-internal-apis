<?php

$keys = require APP_ROOT . '/keys.php';

$dev = true;

return [
    'dev'    => $dev,
    'spaces' => [
        'credentials' => [
            'key'    => 'KOWTSWXXMKRJFEXMSIGK',
            'secret' => $keys['spaces'],
        ],
        'region'      => 'ams3',
        'bucket'      => 'iwgb',
        'cdnUrl'      => 'https://cdn.iwgb.org.uk',
        'shortUrl'    => 'https://iwgb.link',
        'publicRoot'  => 'bucket/',
    ],
];