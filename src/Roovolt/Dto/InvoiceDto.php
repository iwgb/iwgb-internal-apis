<?php

namespace Iwgb\Internal\Roovolt\Dto;

use Carbon\Carbon as DateTime;
use Iwgb\Internal\HttpCompatibleException;

class InvoiceDto extends AbstractDto {

    public string $id;

    public string $status;

    public ?string $hash;

    public ?DateTime $start;

    public ?DateTime $end;

    /** @var ShiftDto[] */
    public array $shifts;

    /** @var AdjustmentDto[] */
    public array $adjustments;

    /**
     * InvoiceDto constructor.
     * @param array $data
     * @throws HttpCompatibleException
     */
    public function __construct(array $data) {
        parent::__construct($data);

        $this->id = $this->required('id');
        $this->status = $this->required('status');
        $this->hash = $this->status
            ? $this->required('hash')
            : null;
        $this->start = DateTime::create($this->required('start'));
        $this->end = DateTime::create($this->required('end'));
        $this->shifts = $this->collection('shifts', ShiftDto::class);
        $this->adjustments = $this->collection('adjustments', AdjustmentDto::class);
    }

    public function toArray(SaveInvoiceDataDto $parent): array {
        return [
            'Invoice ID' => $this->id,
            'Rider ID' => $parent->riderId,
            'Vehicle' => $parent->vehicle,
            'Zone' => $parent->zone,
            'Status' => $this->status,
            'Hash' => $this->hash,
            'Start' => $this->start->toDateString(),
            'End' => $this->end->toDateString(),
        ];
    }

}