<?php

namespace Iwgb\Internal\Media;

use Iwgb\Internal\AbstractDispatcher;
use Iwgb\Internal\CorsPreflight;
use Iwgb\Internal\HttpCompatibleException;
use Siler\Route as http;

class Dispatcher extends AbstractDispatcher {

    /**
     * @throws HttpCompatibleException
     * @noinspection PhpDocRedundantThrowsInspection
     */
    public function __invoke(): void {
        http\get('/media/api/files', $this->handle(Handler\GetAll::class));
        http\post('/media/api/files', $this->handle(Handler\Create::class));
        http\delete("/media/api/files/(?'id'[A-z0-9=]+)", $this->handle(Handler\Delete::class));
        http\post('/media/api/getSignedUrl', $this->handle(Handler\GetUploadUrl::class));

        http\options('/media/api/.*', $this->handle(CorsPreflight::class));

        http\get('/media/api/health', $this->handle(Handler\Health::class));
    }
}