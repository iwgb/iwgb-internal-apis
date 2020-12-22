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
    ],
    'api' => [
        'key' => $_ENV['MEDIA_FILES_API_KEY'],
    ],
];