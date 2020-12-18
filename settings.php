<?php

$dev = $_ENV['ENVIRONMENT'] === 'dev';

return [
    'dev'    => $dev,
    'spaces' => [
        'credentials' => [
            'key'    => $_ENV['SPACES_API_KEY_NAME'],
            'secret' => $_ENV['SPACES_API_KEY'],
        ],
        'region'      => $_ENV['S3_REGION'],
        'bucket'      => $_ENV['S3_BUCKET'],
        'publicRoot'  => $_ENV['S3_PUBLIC_ROOT']
    ],
    'api' => [
        'key' => $_ENV['FILES_API_KEY']
    ],
];