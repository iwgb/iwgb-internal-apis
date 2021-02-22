<?php

namespace Iwgb\Internal\Provider;

use Guym4c\Airtable\Airtable;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RoovoltAirtableProvider implements ServiceProviderInterface {

    /**
     * {@inheritdoc}
     */
    public function register(Container $c) {
        $c[Provider::ROOVOLT_AIRTABLE] = fn (): Airtable =>
        new Airtable(
            $c[Provider::SETTINGS]['roovolt']['airtable']['key'],
            $c[Provider::SETTINGS]['roovolt']['airtable']['base'],
            null,
            [],
            'https://outbound.iwgb.org.uk/v0',
            [
                'X-Proxy-Auth' => $c[Provider::SETTINGS]['roovolt']['airtable']['proxyKey'],
                'X-Proxy-Destination-Key' => 'airtable',
            ],
            false
        );
    }
}