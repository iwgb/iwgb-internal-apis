<?php

namespace Iwgb\Internal;

use Exception;
use Teapot\StatusCode;
use Throwable;

class HttpCompatibleException extends Exception {

    private int $httpStatus;

    public function __construct(
        string $message = "",
        int $statusCode = StatusCode::INTERNAL_SERVER_ERROR,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $statusCode, $previous);

        $this->httpStatus = $statusCode;
    }

    public function getHttpStatus(): int {
        return $this->httpStatus;
    }
}