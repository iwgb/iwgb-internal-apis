<?php

namespace Iwgb\Media\Handler;

use Iwgb\Media\HttpCompatibleException;
use Siler\Http\Request;
use Siler\Http\Response;
use Teapot\StatusCode;

class Create extends AbstractApiHandler {

    /**
     * {@inheritdoc}
     * @throws HttpCompatibleException
     */
    public function __invoke(array $args): void {
        $this->authorise();

        $key = Request\json()['key'] ?? '';

        $this->validateObjectKey($key);

        self::withCors();
        Response\json([
            'id' => base64_encode($key),
            'key' => $key,
        ], StatusCode::CREATED);
    }
}