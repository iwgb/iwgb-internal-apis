<?php

namespace Iwgb\Internal\Media\Handler;

use Siler\Http\Response;

class Health extends AbstractApiHandler {

    public function __invoke(array $args): void {
        $this->store->doesBucketExist($this->bucket);
        Response\no_content();
    }
}