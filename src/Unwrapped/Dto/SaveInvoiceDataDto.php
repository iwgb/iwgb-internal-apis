<?php

namespace Iwgb\Internal\Unwrapped\Dto;

use Iwgb\Internal\AbstractDto;

class SaveInvoiceDataDto extends AbstractDto {

    public string $market;

    public string $area;

    public string $courierId;

    public string $vehicle;

    /** @var InvoiceDto[] */
    public array $invoices;
}