<?php

namespace Iwgb\Internal\Unwrapped\Dto;

use Iwgb\Internal\AbstractDto;
use JsonSerializable;

class ShiftDto extends AbstractDto implements JsonSerializable {

    public string $start;

    public string $end;

    public int $orders;

    public float $pay;

    public float $hours;

    public function jsonSerialize(): array {
        return [
            'start' => $this->start,
            'end' => $this->end,
            'hours' => $this->hours,
            'orders' => $this->orders,
            'pay' => $this->pay,
        ];
    }
}