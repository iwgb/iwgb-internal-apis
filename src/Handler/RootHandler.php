<?php

namespace Iwgb\Media\Handler;

use Aws\S3\S3Client;
use Pimple\Container;


abstract class RootHandler {

    protected S3Client $store;

    protected array $settings;

    protected string $bucket;

    public function __construct(Container $c) {
        $this->store = $c['cdn'];
        $this->settings = $c['settings'];
        $this->bucket = $this->settings['spaces']['bucket'];
    }


    /**
     * @param array $args
     */
    abstract public function __invoke(array $args): void;
}
