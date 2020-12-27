<?php

namespace Iwgb\Internal\Roovolt\Handler;

use Iwgb\Internal\HttpCompatibleException;
use Iwgb\Internal\Roovolt\Dto\RetrieveInvoiceDto;
use Siler\Http\Response;
use Teapot\StatusCode;

class RetrieveInvoice extends RootHandler {

    private const URL_EXPIRY = '+1 minute';

    private const INVALID_KEY_ERROR = "You're not allowed to do that";
    private const FILE_NOT_FOUND_ERROR = 'That file does not exist';

    /**
     * @param array $args
     * @throws HttpCompatibleException
     */
    public function __invoke(array $args): void {
        $data = new RetrieveInvoiceDto();

        if ($data->key !== $this->settings['roovolt']['invoiceKey']) {
            throw new HttpCompatibleException(
                self::INVALID_KEY_ERROR,
                StatusCode::FORBIDDEN,
            );
        }

        $key = self::BUCKET_PREFIX . self::getInvoiceFilename($data->riderId, $data->invoiceId);

        if (!$this->store->doesObjectExist($this->bucket, $key)) {
            throw new HttpCompatibleException(
                self::FILE_NOT_FOUND_ERROR,
                StatusCode::NOT_FOUND,
            );
        }

        Response\redirect(
            (string) $this->store->createPresignedRequest(
                $this->store->getCommand('GetObject', [
                    'Bucket' => $this->bucket,
                    'Key' => $key,
                ]),
                self::URL_EXPIRY,
            )->getUri(),
        );
    }
}