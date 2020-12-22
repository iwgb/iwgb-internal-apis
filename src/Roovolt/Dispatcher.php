<?php

namespace Iwgb\Internal\Roovolt;

use Iwgb\Internal\CorsPreflight;
use Pimple\Container;
use Siler\Route as http;

class Dispatcher {

    private static function dispatch(Container $c): void {
        http\post('/roovolt/api/getInvoiceUploadUrls', new UploadInvoices($c));

        http\options('/roovolt/api/.*', new CorsPreflight($c));
    }

    /**
     * @param $c
     */
    public function __invoke($c): void {
        self::dispatch($c);
    }
}