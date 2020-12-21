<?php

namespace Iwgb\Internal\Media;

use Exception;
use Pimple\Container;
use Siler\Http\Response;
use Siler\Route as http;
use Teapot\StatusCode;

class Dispatcher {

    /**
     * @param Container $c
     * @throws HttpCompatibleException
     * @noinspection PhpDocRedundantThrowsInspection
     */
    private static function dispatch(Container $c) {
        http\get('/media/api/files', new Handler\GetAll($c));
        http\post('/media/api/files', new Handler\Create($c));
        http\delete("/media/api/files/(?'id'[A-z0-9=]+)", new Handler\Delete($c));
        http\post('/media/api/getSignedUrl', new Handler\GetUploadUrl($c));

        http\options('/media/api/.*', new Handler\CorsPreflight($c));

        http\get('/media/api/health', new Handler\Health($c));
    }

    public function __invoke($c): void {
        try {
            self::dispatch($c);
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
    }
}