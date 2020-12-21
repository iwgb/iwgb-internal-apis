<?php

$c = require __DIR__ . '/../bootstrap.php';

use Iwgb\Internal\Media;
use Siler\Container as Router;
use Siler\Http\Response;
use Siler\Route as http;
use Teapot\StatusCode;

(new Media\Dispatcher())($c);

if (!Router\get(http\DID_MATCH, false)) {
    Response\output('not found', StatusCode::NOT_FOUND);
}
