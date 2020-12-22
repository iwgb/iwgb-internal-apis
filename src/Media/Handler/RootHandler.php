<?php

namespace Iwgb\Internal\Media\Handler;

use Aws\S3\S3Client;
use Iwgb\Internal\AbstractHandler;
use Pimple\Container;

abstract class RootHandler extends AbstractHandler {

    protected S3Client $store;

    protected string $bucket;

    protected string $publicRoot;

    public function __construct(Container $c) {
        parent::__construct($c);
        $this->store = $c['cdn'];
        $this->bucket = $this->settings['spaces']['bucket'];
        $this->publicRoot = $this->settings['spaces']['publicRoot'];
    }
}
