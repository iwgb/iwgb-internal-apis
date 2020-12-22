<?php

namespace Iwgb\Internal\Roovolt;

use Aws\S3\S3Client;
use Iwgb\Internal\AbstractHandler;
use Pimple\Container;
use Siler\Http\Response;

class UploadInvoices extends AbstractHandler {

    private const BUCKET_PREFIX = 'branch/clb/invoices/';
    private const URL_EXPIRY = '+3 minutes';
    private const INVOICE_ID_PREFIX = 'IN';

    private S3Client $store;

    public function __construct(Container $c) {
        parent::__construct($c);

        $this->store = $c['cdn'];
    }

    public function __invoke(array $args): void {
        $data = new GetInvoiceUrlDto();

        $files = [];
        foreach ($data->invoices as $invoice) {
            $invoiceId = self::getInvoiceId();
            $files[$invoice] = [
                'id' => $invoiceId,
                'url' => (string) $this->store->createPresignedRequest(
                    $this->store->getCommand('PutObject', [
                        'Bucket' => $this->settings['spaces']['bucket'],
                        'Key' => self::BUCKET_PREFIX . "{$data->riderId}/{$invoiceId}.pdf",
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