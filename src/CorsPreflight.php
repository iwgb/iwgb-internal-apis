<?php

namespace Iwgb\Internal;

use Siler\Http\Response;

class CorsPreflight extends AbstractHandler {

    /**
     * {@inheritdoc}
     */
    public function __invoke(array $args): void {
        self::withCors();
        Response\no_content();
    }
}