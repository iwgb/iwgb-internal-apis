<?php

namespace Iwgb\Internal\Unwrapped\Dto;

use Iwgb\Internal\AbstractDto;
use Iwgb\Internal\Entity\Invoice;
use Iwgb\Internal\Roovolt\Dto\AdjustmentDto;
use Iwgb\Internal\Roovolt\Dto\ShiftDto;

class InvoiceDto extends AbstractDto {

    public string $id;

    public string $status;

    public ?string $hash;

    public ?string $start;

    public ?string $end;

    /** @var ShiftDto[] */
    public array $shifts;

    /** @var AdjustmentDto[] */
    public array $adjustments;

    public function toEntity(SaveInvoiceDataDto $parent): Invoice {
        return (new Invoice())
            ->setId($this->id)
            ->setStatus($this->status)
            ->setHash($this->hash)
            ->setMarket($parent->market)
            ->setArea($parent->area)
            ->setCourierId($parent->courierId)
            ->setVehicle($parent->vehicle);
    }
}