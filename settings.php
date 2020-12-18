<?php

$dev = true;

return [
    'dev'    => $dev,
    'spaces' => [
        'credentials' => [
            'key'    => 'KOWTSWXXMKRJFEXMSIGK',
            'secret' => $_ENV['SPACES_API_KEY'],
        ],
        'region'      => 'ams3',
        'bucket'      => 'iwgb',
        'publicRoot'  => 'bucket/',
    ],
    'api' => [
        'key' => $_ENV['FILES_API_KEY']
    ],
];