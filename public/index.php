<?php

$c = require __DIR__ . '/../bootstrap.php';

use Iwgb\Media\Handler;
use Iwgb\Media\HttpCompatibleException;
use Pimple\Container;
use Siler\Container as Router;
use Siler\Http\Response;
use Siler\Route as http;
use Teapot\StatusCode;

/**
 * @param Container $c
 * @throws HttpCompatibleException
 */
function dispatch(Container $c) {
    http\get('/api/files', new Handler\GetAll($c));
    http\post('/api/files', new Handler\Create($c));
    http\delete("/api/files/(?'id'[A-z0-9=]+)", new Handler\Delete($c));
    http\post('/api/getSignedUrl', new Handler\GetUploadUrl($c));

    http\options('/api/.*', new Handler\CorsPreflight($c));

    http\get('/api/health', new Handler\Health($c));
}

try {
    dispatch($c);
} catch (HttpCompatibleException $e) {
    Response\json(
        ['error' => $e->getMessage()],
        $e->getHttpStatus(),
    );
} catch (Exception $e) {
    Response\json(
        ['error' => $e->getMessage()],
        StatusCode::INTERNAL_SERVER_ERROR,
    );
}

if (!Router\get(http\DID_MATCH, false)) {
    Response\output('not found', StatusCode::NOT_FOUND);
}
