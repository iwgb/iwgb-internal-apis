<?php

namespace Iwgb\Internal;

use JsonSerializable;
use ReflectionClass;
use Siler\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\DataTransferObjectError;
use Teapot\StatusCode;

abstract class AbstractDto extends DataTransferObject implements JsonSerializable {

    /**
     * @return static
     * @throws HttpCompatibleException
     */
    public static function fromRequest(): self {
        try {
            return new static(Request\json());
        } catch (DataTransferObjectError $e) {
            throw new HttpCompatibleException(
                "Payload does not meet schema",
                StatusCode::BAD_REQUEST,
            );
        }
    }

    public function jsonSerialize(): array {
        $values = [];
        foreach ((new ReflectionClass($this))->getProperties() as $property) {
            $key = $property->getName();
            $value = $property->getValue();
            if ($value instanceof self) {
                $values[$key] = $value->jsonSerialize();
            } else {
                $values[$key] = $value;
            }
        }
        return $values;
    }
}