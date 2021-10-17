<?php

namespace Iwgb\Internal\Roovolt\Handler;

use Iwgb\Internal\Entity\LegacyInvoice;
use Iwgb\Internal\HttpCompatibleException;
use Siler\Http\Request;
use Siler\Http\Response;
use Teapot\StatusCode;

class GenerateJson extends AbstractPersistingHandler {

    /**
     * {@inheritdoc}
     * @throws HttpCompatibleException
     */
    public function __invoke(array $args): void {
        if (Request\header('authorization') !== "Bearer {$this->settings['roovolt']['dataKey']}") {
            throw new HttpCompatibleException(
                self::INVALID_KEY_ERROR,
                StatusCode::FORBIDDEN,
            );
        }

        $invoices = [];
        foreach ($this->em->getRepository(LegacyInvoice::class)->findAll() as $invoiceEntity) {
            /** @var $invoiceEntity LegacyInvoice */
            $invoices[] = json_decode($invoiceEntity->getData());
        }

        Response\json($invoices);
    }
}