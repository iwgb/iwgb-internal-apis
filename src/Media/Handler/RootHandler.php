<?php

namespace Iwgb\Internal\Media\Handler;

use Aws\S3\S3Client;
use Pimple\Container;

abstract class RootHandler {

    protected S3Client $store;

    protected array $settings;

    protected string $bucket;

    protected string $publicRoot;

    public function __construct(Container $c) {
        $this->store = $c['cdn'];
        $this->settings = $c['settings'];
        $this->bucket = $this->settings['spaces']['bucket'];
        $this->publicRoot = $this->settings['spaces']['publicRoot'];
    }


    /**
     * @param array $args
     */
    abstract public function __invoke(array $args): void;
}
