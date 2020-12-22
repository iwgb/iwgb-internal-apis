<?php

namespace Iwgb\Internal\Media;

use Iwgb\Internal\CorsPreflight;
use Iwgb\Internal\HttpCompatibleException;
use Pimple\Container;
use Siler\Route as http;

class Dispatcher {

    /**
     * @param Container $c
     * @throws HttpCompatibleException
     * @noinspection PhpDocRedundantThrowsInspection
     */
    private static function dispatch(Container $c): void {
        http\get('/media/api/files', new Handler\GetAll($c));
        http\post('/media/api/files', new Handler\Create($c));
        http\delete("/media/api/files/(?'id'[A-z0-9=]+)", new Handler\Delete($c));
        http\post('/media/api/getSignedUrl', new Handler\GetUploadUrl($c));

        http\options('/media/api/.*', new CorsPreflight($c));

        http\get('/media/api/health', new Handler\Health($c));
    }

    /**
     * @param $c
     * @throws HttpCompatibleException
     */
    public function __invoke($c): void {
        self::dispatch($c);
    }
}