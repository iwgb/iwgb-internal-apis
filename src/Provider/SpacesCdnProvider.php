<?php

namespace Iwgb\Media\Provider;

use Aws\S3\S3Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class SpacesCdnProvider implements ServiceProviderInterface {

    /**
     * @inheritDoc
     */
    public function register(Container $c) {
        $c['cdn'] = fn(Container $c): S3Client => new S3Client([
            'version'    => 'latest',
            'region'     => $c['settings']['spaces']['region'],
            'endpoint'   => 'https://' . $c['settings']['spaces']['region'] . '.digitaloceanspaces.com',
            'credentials'=> $c['settings']['spaces']['credentials'],
        ]);
    }
}