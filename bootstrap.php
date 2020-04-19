<?php

use Iwgb\Media\Provider;

define('APP_ROOT', __DIR__);

require APP_ROOT . '/vendor/autoload.php';

return (new \Pimple\Container([
    'settings' => require APP_ROOT . '/settings.php',
]))->register(new Provider\TwigTemplateProvider())
    ->register(new Provider\SpacesCdnProvider())
    ->register(new Provider\DiactorosPsr7Provider())
    ->register(new Provider\CarbonDateTimeProvider());
