<?php

namespace Iwgb\Media\Provider;

use Carbon;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class CarbonDateTimeProvider implements ServiceProviderInterface {

    /**
     * @inheritDoc
     */
    public function register(Container $c) {
        $c['datetime'] = fn(): Carbon\Factory => new Carbon\Factory();
    }
}