<?php

$dev = $_ENV['ENVIRONMENT'] === 'dev';

return [
    'dev'    => $dev,
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
        'airtable' => [
            'key' => $_ENV['AIRTABLE_API_KEY'],
            'base' => $_ENV['ROOVOLT_AIRTABLE_BASE_ID'],
            'proxyKey' => $_ENV['AIRTABLE_PROXY_KEY'],
        ],
        'invoiceKey' => $_ENV['ROOVOLT_INVOICE_RETRIEVAL_KEY'],
    ],
];