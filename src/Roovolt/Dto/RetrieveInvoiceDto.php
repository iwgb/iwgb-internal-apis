<?php

namespace Iwgb\Internal\Roovolt\Dto;

use Iwgb\Internal\HttpCompatibleException;

class RetrieveInvoiceDto extends AbstractDto {

    public string $riderId;

    public string $invoiceId;

    public string $key;

    /**
     * RetrieveInvoiceDto constructor.
     * @throws HttpCompatibleException
     */
    public function __construct() {
        $data = self::fromGetParams(['riderId', 'invoiceId', 'key']);
        parent::__construct($data);

        $this->riderId = $this->required('riderId');
        $this->invoiceId = $this->required('invoiceId');
        $this->key = $this->required('key');
    }
}