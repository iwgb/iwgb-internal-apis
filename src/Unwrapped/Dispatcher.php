<?php

namespace Iwgb\Internal\Unwrapped;

use Iwgb\Internal\AbstractDispatcher;
use Iwgb\Internal\CorsPreflight;
use Siler\Route as http;

class Dispatcher extends AbstractDispatcher {

    public function __invoke(): void {
        http\post('/unwrapped/api/getUploadUrls', $this->handle(Handler\GetUploadUrls::class));
        http\post('/unwrapped/api/saveInvoiceData', $this->handle(Handler\SaveInvoiceData::class));

        http\options('/unwrapped/api/.*', $this->handle(CorsPreflight::class));
    }
}