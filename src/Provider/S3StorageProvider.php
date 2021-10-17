<?php

namespace Iwgb\Internal\Provider;

use Aws\S3\S3Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class S3StorageProvider implements ServiceProviderInterface {

    /**
     * @inheritDoc
     */
    public function register(Container $c) {
        $c[Provider::S3] = fn(Container $c): S3Client => new S3Client([
            'version'    => 'latest',
            'region'     => $c[Provider::SETTINGS]['spaces']['region'],
            'endpoint'   => 'https://' . $c[Provider::SETTINGS]['spaces']['region'] . '.digitaloceanspaces.com',
            'credentials'=> $c[Provider::SETTINGS]['spaces']['credentials'],
        ]);
    }

    public static function sanitiseFilename(string $s): string {
        return preg_replace("[^A-z0-9-!_.*'\(\)]", '', $s);
    }

    public static function sanitiseKey(string $s): string {
        return preg_replace("[^A-z0-9-!_.*'\(\)/]", '', $s);
    }
}