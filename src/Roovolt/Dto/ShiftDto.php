<?php

namespace Iwgb\Internal\Roovolt\Dto;

use Carbon\Carbon as DateTime;
use Iwgb\Internal\HttpCompatibleException;

class ShiftDto extends AbstractDto {

    public DateTime $start;

    public DateTime $end;

    public int $orders;

    public float $total;

    /**
     * ShiftDto constructor.
     * @param array $data
     * @throws HttpCompatibleException
     */
    public function __construct(array $data) {
        parent::__construct($data);

        $this->start = DateTime::create($this->required('start'));
        $this->end = DateTime::create($this->required('end'));
        $this->orders = $this->required('orders');
        $this->total = $this->required('total');
    }

    public function toArray(): array {
        return [
            'Start' => $this->start->toIso8601ZuluString(),
            'End' => $this->end->toIso8601ZuluString(),
            'Hours' => round($this->start->diffInMinutes($this->end) / 60, 2),
            'Orders' => $this->orders,
            'Pay' => $this->total,
        ];
    }

}