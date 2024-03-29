<?php

namespace Iwgb\Internal\Roovolt\Handler;

use Aws\S3\S3Client;
use Iwgb\Internal\AbstractHandler;
use Iwgb\Internal\Provider\Provider;
use Iwgb\Internal\Provider\S3StorageProvider as S3;
use Pimple\Container;

abstract class RootHandler extends AbstractHandler {

    protected const BUCKET_PREFIX = 'branch/clb/invoices/';
    protected const INVALID_KEY_ERROR = "You're not allowed to do that";

    protected S3Client $store;

    protected string $bucket;

    public function __construct(Container $c) {
        parent::__construct($c);

        $this->store = $c[Provider::S3];
        $this->bucket = $this->settings['spaces']['bucket'];
    }

    protected static function getInvoiceFilename(string $riderId, string $invoiceId): string {
        return S3::sanitiseFilename($riderId) . '/' . S3::sanitiseFilename($invoiceId) . '.pdf';
    }
}