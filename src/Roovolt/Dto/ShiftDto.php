<?php

namespace Iwgb\Internal\Roovolt\Dto;

use Carbon\Carbon as DateTime;
use Iwgb\Internal\HttpCompatibleException;
use JsonSerializable;

class ShiftDto extends AbstractDto implements JsonSerializable {

    private const API_DATE_FORMAT = 'Y-m-d';
    private const API_TIME_FORMAT = 'H:i:s.v';

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

    public function jsonSerialize(): array {
        return [
            'Start' => self::getApiDateTime($this->start),
            'End' => self::getApiDateTime($this->end),
            'Hours' => round($this->start->diffInMinutes($this->end) / 60, 2),
            'Orders' => $this->orders,
            'Pay' => $this->total,
        ];
    }

    private function getApiDateTime(DateTime $datetime): string {
        return $datetime->format(self::API_DATE_FORMAT) . 'T' .
            $datetime->format(self::API_TIME_FORMAT) . 'Z';
    }

}