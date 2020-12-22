<?php

namespace Iwgb\Internal\Media\Handler;

use Iwgb\Internal\HttpCompatibleException;
use Siler\Http\Response;

class GetAll extends AbstractApiHandler {

    /**
     * {@inheritdoc}
     * @throws HttpCompatibleException
     */
    public function __invoke(array $args): void {
        $this->authorise();

        $objects = $this->store->listObjects([
            'Bucket' => $this->bucket,
            'Prefix' => $this->publicRoot,
        ])->toArray()['Contents'];

        $files = [];
        foreach ($objects as $object) {
            if ($object['Key'] !== $this->publicRoot) {
                $files[] = array_merge(
                    ['id' => base64_encode($object['Key'])],
                    self::allTitleToCamelCase($object),
                );
            }
        }

        self::withCors();
        Response\json($files);
    }
}