<?php

namespace Iwgb\Internal\Roovolt\Handler;

use Aws\S3\S3Client;
use Iwgb\Internal\AbstractHandler;
use Iwgb\Internal\Provider\Provider;
use Iwgb\Internal\Provider\SpacesCdnProvider as S3;
use Pimple\Container;

abstract class RootHandler extends AbstractHandler {

    protected const BUCKET_PREFIX = 'branch/clb/invoices/';

    protected S3Client $store;

    protected string $bucket;

    public function __construct(Container $c) {
        parent::__construct($c);

        $this->store = $c[Provider::SPACES];
        $this->bucket = $this->settings['spaces']['bucket'];
    }

    protected static function getInvoiceFilename(string $riderId, string $invoiceId): string {
        return S3::sanitiseFilename($riderId) . '/' . S3::sanitiseFilename($invoiceId) . '.pdf';
    }
}