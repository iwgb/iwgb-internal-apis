<?php

namespace Iwgb\Internal\Roovolt\Dto;

use Iwgb\Internal\HttpCompatibleException;
use Siler\Http\Request;
use Teapot\StatusCode;

abstract class AbstractDto {

    private array $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    /**
     * @param string $key
     * @return mixed
     * @throws HttpCompatibleException
     */
    protected function required(string $key) {
        if (empty($this->data[$key])) {
            throw new HttpCompatibleException(
                StatusCode::BAD_REQUEST,
                "{$key} is required",
            );
        }
        return $this->data[$key];
    }

    protected function collection(string $key, string $class): array {
        $items = [];
        foreach ($this->data[$key] ?? [] as $item) {
            $items[] = new $class($item);
        }
        return $items;
    }

    protected static function fromGetParams(array $keys): array {
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = Request\get($key);
        }
        return $data;
    }
}