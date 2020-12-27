<?php

namespace Iwgb\Internal\Roovolt\Handler;

use Iwgb\Internal\Roovolt\Dto\GetInvoiceUploadUrlsDto;
use Siler\Http\Response;

class GetInvoiceUploadUrls extends RootHandler {

    private const URL_EXPIRY = '+3 minutes';
    private const INVOICE_ID_PREFIX = 'IN';

    public function __invoke(array $args): void {
        $data = new GetInvoiceUploadUrlsDto();

        $files = [];
        foreach ($data->invoices as $invoice) {
            $invoiceId = self::getInvoiceId();
            $files[$invoice] = [
                'id' => $invoiceId,
                'url' => (string) $this->store->createPresignedRequest(
                    $this->store->getCommand('PutObject', [
                        'Bucket' => $this->bucket,
                        'Key' => self::BUCKET_PREFIX . self::getInvoiceFilename($data->riderId, $invoiceId),
                        'ACL' => 'private',
                        'ContentType' => 'application/pdf',
                    ]),
                    self::URL_EXPIRY,
                )->getUri(),
            ];
        }

        self::withCors();
        Response\json([
            'riderId' => $data->riderId,
            'invoices' => $files,
        ]);
    }


    /**
     * @return string
     */
    private static function getInvoiceId(): string {
        return self::INVOICE_ID_PREFIX . '-' . uniqid();
    }
}