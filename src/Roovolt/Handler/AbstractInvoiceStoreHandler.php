<?php

namespace Iwgb\Internal\Roovolt\Handler;

use Guym4c\Airtable\Airtable;
use Iwgb\Internal\Provider\Provider;
use Pimple\Container;
use Predis as Redis;

abstract class AbstractInvoiceStoreHandler extends RootHandler {

    protected Airtable $airtable;
    protected Redis\Client $redis;

    public function __construct(Container $c) {
        parent::__construct($c);

        $this->airtable = $c[Provider::ROOVOLT_AIRTABLE];
        $this->redis = $c[Provider::REDIS];
    }
}