<?php

namespace Iwgb\Internal\Unwrapped\Handler;

use Doctrine\ORM\EntityManager;
use Iwgb\Internal\Provider\Provider;
use Pimple\Container;

abstract class AbstractPersistingHandler extends RootHandler {

    protected EntityManager $em;

    public function __construct(Container $c) {
        parent::__construct($c);

        $this->em = $c[Provider::DATABASE];
    }
}