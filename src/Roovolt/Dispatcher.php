<?php

namespace Iwgb\Internal\Roovolt;

use Iwgb\Internal\AbstractDispatcher;
use Iwgb\Internal\CorsPreflight;
use Siler\Route as http;

class Dispatcher extends AbstractDispatcher {

    public function __invoke(): void {
        http\post('/roovolt/api/getInvoiceUploadUrls', $this->handle(Handler\GetInvoiceUploadUrls::class));
        http\post('/roovolt/api/saveInvoiceData', $this->handle(Handler\SaveInvoiceData::class));
        http\get('/roovolt/invoice', $this->handle(Handler\RetrieveInvoice::class));
        http\get('/roovolt/report', $this->handle(Handler\GenerateReport::class));
        http\post('/roovolt/api/signUp', $this->handle(Handler\SignUp::class));
        http\get('/roovolt/api/data', $this->handle(Handler\GenerateJson::class));

        http\options('/roovolt/api/.*', $this->handle(CorsPreflight::class));
    }
}