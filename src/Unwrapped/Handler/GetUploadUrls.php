<?php

namespace Iwgb\Internal\Unwrapped\Handler;

use Iwgb\Internal\HttpCompatibleException;
use Iwgb\Internal\Unwrapped\Dto\GetUploadUrlsDto;
use Siler\Http\Response;

class GetUploadUrls extends RootHandler {

    private const URL_EXPIRY = '+3 minutes';
    private const INVOICE_ID_PREFIX = 'IN';

    /**
     * {@inheritdoc}
     * @throws HttpCompatibleException
     */
    public function __invoke(array $args): void {
        $data = GetUploadUrlsDto::fromRequest();

        $files = [];
        for ($i = 0; $i < $data->count; $i++) {
            $invoiceId = self::getInvoiceId();
            $files[] = [
                'id' => $invoiceId,
                'url' => (string) $this->s3->createPresignedRequest(
                    $this->s3->getCommand('PutObject', [
                        'Bucket' => $this->bucket,
                        'Key' => self::BUCKET_PREFIX . self::getInvoiceFilename($data->courierId, $invoiceId),
                        'ACL' => 'private',
                        'ContentType' => 'application/pdf',
                    ]),
                    self::URL_EXPIRY,
                )->getUri(),
            ];
        }

        self::withCors();
        Response\json($files);
    }


    /**
     * @return string
     */
    private static function getInvoiceId(): string {
        return self::INVOICE_ID_PREFIX . '-' . uniqid();
    }
}