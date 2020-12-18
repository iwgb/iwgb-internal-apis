<?php

use Dotenv\Dotenv;
use Iwgb\Media\Provider;
use Pimple\Container;

define('APP_ROOT', __DIR__);

require APP_ROOT . '/vendor/autoload.php';

Dotenv::createImmutable(APP_ROOT)->load();

return (new Container([
    'settings' => require APP_ROOT . '/settings.php',
]))->register(new Provider\TwigTemplateProvider())
    ->register(new Provider\SpacesCdnProvider())
    ->register(new Provider\DiactorosPsr7Provider())
    ->register(new Provider\CarbonDateTimeProvider());
