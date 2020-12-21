<?php

namespace Iwgb\Internal\Media\Handler;

use Iwgb\Internal\Media\HttpCompatibleException;
use Siler\Http\Request;
use Siler\Http\Response;

class GetUploadUrl extends AbstractApiHandler {

    private const URL_EXPIRY = '+1 minute';

    /**
     * {@inheritdoc}
     * @throws HttpCompatibleException
     */
    public function __invoke(array $args): void {
        $this->authorise();

        $body = Request\json();
        $key = $body['key'] ?? '';
        $mime = $body['mime'] ?? '';

        $this->validateObjectKey($key);

        self::withCors();
        Response\json([
            'url' => (string) $this->store->createPresignedRequest(
                $this->store->getCommand('PutObject', [
                    'Bucket' => $this->bucket,
                    'Key' => $key,
                    'ACL' => 'public-read',
                    'ContentType' => $mime,
                ]),
                self::URL_EXPIRY,
            )->getUri(),
        ]);
    }
}