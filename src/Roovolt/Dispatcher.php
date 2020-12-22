<?php

namespace Iwgb\Internal\Roovolt;

use Pimple\Container;
use Siler\Route as http;

class Dispatcher {

    private static function dispatch(Container $c): void {
        http\post('/roovolt/api/getInvoiceUploadUrls', new UploadInvoices($c));
    }

    /**
     * @param $c
     */
    public function __invoke($c): void {
        self::dispatch($c);
    }
}