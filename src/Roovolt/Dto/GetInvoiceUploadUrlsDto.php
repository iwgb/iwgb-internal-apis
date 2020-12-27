<?php

namespace Iwgb\Internal\Roovolt\Dto;

use Iwgb\Internal\HttpCompatibleException;
use Siler\Http\Request;
use Teapot\StatusCode;

class GetInvoiceUploadUrlsDto {

    private const RIDER_ID_PREFIX = 'RI';

    private const INVALID_FILE_FORMAT_ERROR = 'Files must be PDFs';

    public string $riderId;

    /** @var string[] */
    public array $invoices;

    /**
     * GetInvoiceUrlDto constructor.
     * @throws HttpCompatibleException
     */
    public function __construct() {
        $data = Request\json();

        $this->riderId = $data['riderId'] ?? false
            ? self::RIDER_ID_PREFIX . "-{$data['riderId']}"
            : self::RIDER_ID_PREFIX . '-XX' . uniqid();

        $invoices = $data['invoices'] ?? [];
        foreach ($invoices as $invoice) {
            $parts = explode('.', $invoice);
            if (end($parts) !== 'pdf') {
                throw new HttpCompatibleException(
                    self::INVALID_FILE_FORMAT_ERROR,
                    StatusCode::BAD_REQUEST,
                );
            }
        }
        $this->invoices = $invoices;
    }
}