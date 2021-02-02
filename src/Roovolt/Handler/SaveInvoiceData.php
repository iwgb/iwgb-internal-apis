<?php

namespace Iwgb\Internal\Roovolt\Handler;

use Iwgb\Internal\HttpCompatibleException;
use Iwgb\Internal\Provider\Provider;
use Iwgb\Internal\Roovolt\Dto\SaveInvoiceDataDto;
use Pimple\Container;
use Predis as Redis;
use Siler\Http\Request;
use Siler\Http\Response;
use Teapot\StatusCode;

class SaveInvoiceData extends RootHandler {

    private Redis\Client $redis;

    public function __construct(Container $c) {
        parent::__construct($c);

        $this->redis = $c[Provider::REDIS];
    }

    /**
     * {@inheritdoc}
     * @throws HttpCompatibleException
     */
    public function __invoke(array $args): void {
        if (Request\get('key') !== $this->settings['api']['key']) {
            throw new HttpCompatibleException(
                self::INVALID_KEY_ERROR,
                StatusCode::FORBIDDEN,
            );
        }

        $data = new SaveInvoiceDataDto();

        $invoicesById = [];
        foreach ($data->invoices as $invoice) {
            $invoicesById[$invoice->id] = json_encode($invoice->serialize($data));
        }
        $this->redis->mset($invoicesById);

        Response\no_content();
    }
}