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
        $this->hash = $this->status === 'success'
            ? $this->required('hash')
            : null;
        $this->start = DateTime::create($data['start'] ?? '');
        $this->end = DateTime::create($data['end'] ?? '');
        $this->shifts = $this->collection('shifts', ShiftDto::class);
        $this->adjustments = $this->collection('adjustments', AdjustmentDto::class);
    }

    public function serialize(SaveInvoiceDataDto $parent): array {
        $shifts = [];
        $adjustments = [];
        foreach ($this->shifts as $shift) {
            $shifts[] = $shift->jsonSerialize();
        }
        foreach ($this->adjustments as $adjustment) {
            $adjustments[] = $adjustment->jsonSerialize();
        }
        return [
            'id' => $this->id,
            'riderId' => $parent->riderId,
            'vehicle' => $parent->vehicle,
            'zone' => $parent->zone,
            'status' => $this->status,
            'hash' => $this->hash,
            'start' => $this->start->toDateString(),
            'end' => $this->end->toDateString(),
            'shifts' => $shifts,
            'adjustments' => $adjustments,
        ];
    }

}