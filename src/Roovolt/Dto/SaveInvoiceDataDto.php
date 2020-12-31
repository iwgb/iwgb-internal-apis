<?php

namespace Iwgb\Internal\Roovolt\Dto;

use Iwgb\Internal\HttpCompatibleException;
use Siler\Http\Request;

class SaveInvoiceDataDto extends AbstractDto {

    public string $riderId;

    public string $zone;

    public string $vehicle;

    /** @var InvoiceDto[] */
    public array $invoices;

    /**
     * SaveInvoiceDataDto constructor.
     * @throws HttpCompatibleException
     */
    public function __construct() {
        $data = Request\json();
        parent::__construct($data);

        $this->riderId = $this->required('riderId');
        $this->zone = $this->required('zone');
        $this->vehicle = $this->required('vehicle');
        $this->invoices = $this->collection('invoices', InvoiceDto::class);
    }
}