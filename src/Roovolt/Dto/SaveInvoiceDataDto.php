<?php

namespace Iwgb\Internal\Roovolt\Dto;

use Siler\Http\Request;

class SaveInvoiceDataDto extends AbstractDto {

    public string $riderId;

    /** @var InvoiceDto[] */
    public array $invoices;

    public function __construct() {
        $data = Request\json();
        parent::__construct($data);

        $this->riderId = $data['riderId'];
        $this->invoices = $this->collection('invoices', InvoiceDto::class);
    }
}