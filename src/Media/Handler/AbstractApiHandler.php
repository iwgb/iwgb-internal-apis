<?php

namespace Iwgb\Internal\Media\Handler;

use Iwgb\Internal\Media\HttpCompatibleException;
use Siler\Http\Request;
use Siler\Http\Response;
use Teapot\StatusCode;

abstract class AbstractApiHandler extends RootHandler {

    private const EMPTY_OBJECT_ID_ERROR = 'Object ID must be provided';
    private const OBJECT_NOT_IN_PUBLIC_ROOT_ERROR = 'Object must be inside public root';
    private const OBJECT_NOT_FOUND_ERROR = 'Object not found';
    private const INVALID_API_KEY = 'Key not authorised';

    /**
     * @param string $key
     * @param bool $validateExists
     * @throws HttpCompatibleException
     */
    protected function validateObjectKey(string $key, bool $validateExists = false): void {
        if ($key === '') {
            throw new HttpCompatibleException(
                self::EMPTY_OBJECT_ID_ERROR,
                StatusCode::BAD_REQUEST,
            );
        }
        if (substr($key, 0, strlen($this->publicRoot)) !== $this->publicRoot) {
            throw new HttpCompatibleException(
                self::OBJECT_NOT_IN_PUBLIC_ROOT_ERROR,
                StatusCode::FORBIDDEN,
            );
        }
        if (
            $validateExists
            && !$this->store->doesObjectExist($this->bucket, $key)
        ) {
            throw new HttpCompatibleException(
                self::OBJECT_NOT_FOUND_ERROR,
                StatusCode::NOT_FOUND,
            );
        }
    }

    /**
     * @throws HttpCompatibleException
     */
    protected function authorise(): void {
        if (Request\header('authorization') !== "Bearer {$this->settings['api']['key']}") {
            throw new HttpCompatibleException(
                self::INVALID_API_KEY,
                StatusCode::FORBIDDEN,
            );
        }
    }

    protected static function withCors(): void {
        Response\header('access-control-allow-origin', Request\header('origin') ?? '*');
        Response\header('access-control-allow-credentials', 'true');
        Response\header('access-control-allow-headers', 'authorization, content-type');
        Response\header('access-control-allow-methods', 'GET, POST, DELETE, OPTIONS');
    }

    private static function titleToCamelCase(string $s): string {
        return strtolower(substr($s, 0, 1)) . substr($s, 1);
    }

    protected static function allTitleToCamelCase(array $values): array {
        $camels = [];
        foreach ($values as $key => $value) {
            $camels[self::titleToCamelCase($key)] = $value;
        }
        return $camels;
    }
}