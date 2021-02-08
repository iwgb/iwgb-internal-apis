<?php

$isDev = $_ENV['ENVIRONMENT'] === 'dev';

return [
    'dev'    => $isDev,
    'db'       => [
        'devMode'   => $isDev,
        'entityDir'  => APP_ROOT . '/src/Entity',
        'connection' => [
            'driver'   => $_ENV['DB_DRIVER'],
            'host'     => $_ENV['DB_HOST'],
            'port'     => $_ENV['DB_PORT'],
            'dbname'   => $_ENV['DB_NAME'],
            'charset'  => $_ENV['DB_CHARSET'],
            'user'     => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
        ],
    ],
    'spaces' => [
        'credentials' => [
            'key'    => $_ENV['MEDIA_S3_API_KEY_NAME'],
            'secret' => $_ENV['MEDIA_S3_API_KEY'],
        ],
        'region'      => $_ENV['MEDIA_S3_REGION'],
        'bucket'      => $_ENV['MEDIA_S3_BUCKET'],
        'publicRoot'  => $_ENV['MEDIA_S3_PUBLIC_ROOT'],
        'cdn' => [
            'zoneId' => $_ENV['MEDIA_KEYCDN_ZONE_ID'],
            'apiKey' => $_ENV['MEDIA_KEYCDN_API_KEY'],
            'zoneHost' => $_ENV['MEDIA_KEYCDN_ZONE_BASE_URL'],
        ],
    ],
    'api' => [
        'key' => $_ENV['MEDIA_FILES_API_KEY'],
    ],
    'roovolt' => [
        'invoiceKey' => $_ENV['ROOVOLT_INVOICE_RETRIEVAL_KEY'],
    ],
];