<?php

namespace Iwgb\Internal\Unwrapped\Handler;

use Aws\S3\S3Client;
use Iwgb\Internal\AbstractHandler;
use Iwgb\Internal\Provider\Provider;
use Iwgb\Internal\Provider\S3StorageProvider as S3;
use Pimple\Container;

abstract class RootHandler extends AbstractHandler {

    protected const BUCKET_PREFIX = 'campaign/gig-eco-unwrapped/';
    protected const INVALID_KEY_ERROR = "You're not allowed to do that";

    protected S3Client $s3;

    protected string $bucket;

    public function __construct(Container $c) {
        parent::__construct($c);

        $this->s3 = $c[Provider::S3];
        $this->bucket = $this->settings['spaces']['bucket'];
    }

    protected static function getInvoiceFilename(string $courierId, string $invoiceId): string {
        return S3::sanitiseFilename($courierId) . '/' . S3::sanitiseFilename($invoiceId) . '.pdf';
    }
}