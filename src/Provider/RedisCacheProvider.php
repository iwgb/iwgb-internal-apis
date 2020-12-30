<?php

namespace Iwgb\Internal\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Predis as Redis;

class RedisCacheProvider implements ServiceProviderInterface {

    public function register(Container $c) {
        $c[Provider::REDIS] = fn (Container $c): Redis\Client => new Redis\Client([
            'host' => $c[Provider::SETTINGS]['redis']['host'],
            'port' => $c[Provider::SETTINGS]['redis']['port'],
            'password' => $c[Provider::SETTINGS]['redis']['password'],
        ]);
    }
}