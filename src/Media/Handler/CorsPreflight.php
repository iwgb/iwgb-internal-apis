<?php

namespace Iwgb\Internal\Media\Handler;

use Siler\Http\Response;

class CorsPreflight extends AbstractApiHandler {

    /**
     * {@inheritdoc}
     */
    public function __invoke(array $args): void {
        self::withCors();
        Response\no_content();
    }

}