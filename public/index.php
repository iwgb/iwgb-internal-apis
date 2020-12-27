<?php

$c = require __DIR__ . '/../bootstrap.php';

use Iwgb\Internal\Media;
use Iwgb\Internal\HttpCompatibleException;
use Iwgb\Internal\Roovolt;
use Siler\Container as Router;
use Siler\Http\Response;
use Siler\Route as http;
use Teapot\StatusCode;

try {
    (new Media\Dispatcher($c))();
    (new Roovolt\Dispatcher($c))();
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
