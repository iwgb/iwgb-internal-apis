<?php

namespace Iwgb\Internal;

use Pimple\Container;

abstract class AbstractDispatcher {

    private Container $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    abstract public function __invoke(): void;

    protected function handle(string $handler): callable {
        return function ($args) use ($handler) {
            (new $handler($this->container))($args);
        };
    }
}