<?php

namespace Iwgb\Internal\Media\Handler;

use Iwgb\Internal\HttpCompatibleException;
use Siler\Http\Response;

class Delete extends AbstractApiHandler {

    /**
     * {@inheritdoc}
     * @throws HttpCompatibleException
     */
    public function __invoke(array $args): void {
        $this->authorise();

        $key = base64_decode($args['id'] ?? '');

        $this->validateObjectKey($key, true);

        $this->store->deleteObject([
            'Bucket' => $this->bucket,
            'Key' => $key,
        ]);

        self::withCors();
        Response\json(['id' => $args['id']]);
    }
}