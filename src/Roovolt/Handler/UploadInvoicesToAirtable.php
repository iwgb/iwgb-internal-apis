<?php

namespace Iwgb\Internal\Roovolt\Handler;

use Guym4c\Airtable\AirtableApiException;
use Iwgb\Internal\HttpCompatibleException;
use Iwgb\Internal\Roovolt\Table;
use Siler\Http\Request;
use Siler\Http\Response;
use Teapot\StatusCode;

class UploadInvoicesToAirtable extends AbstractInvoiceStoreHandler {

    /**
     * {@inheritdoc}
     * @throws AirtableApiException
     * @throws HttpCompatibleException
     */
    public function __invoke(array $args): void {
        if (Request\get('key') !== $this->settings['roovolt']['invoiceKey']) {
            throw new HttpCompatibleException(
                self::INVALID_KEY_ERROR,
                StatusCode::FORBIDDEN,
            );
        }

        $unprocessedInvoices = $this->airtable->find(Table::INVOICES, 'Status', 'pending');

        if (count($unprocessedInvoices) === 0) {
            return;
        }

        $shifts = [];
        $adjustments = [];
        $invoiceIds = [];
        foreach ($unprocessedInvoices as $airtableInvoice) {
            $invoiceId = $airtableInvoice->getId();
            $invoiceIds[] = $invoiceId;
            $data = json_decode($this->redis->get($invoiceId), true);

            /** @noinspection PhpUndefinedFieldInspection */
            $airtableInvoice->Status = 'success';

            $shifts = array_merge(
                $shifts,
                self::prepareChildren($data['shifts'], $invoiceId),
            );

            $adjustments = array_merge(
                $adjustments,
                self::prepareChildren($data['adjustments'], $invoiceId),
            );
        }

        $this->airtable->createAll(Table::SHIFTS, $shifts);
        $this->airtable->createAll(Table::ADJUSTMENTS, $adjustments);
        $this->airtable->updateAll(Table::INVOICES, $unprocessedInvoices);

        $this->redis->del($invoiceIds);

        Response\no_content();
    }

    private static function prepareChildren(array $items, string $invoiceId): array {
        $processed = [];
        foreach ($items as $item) {
            $processed[] = array_merge(
                $item,
                ['Invoice' => [$invoiceId]],
            );
        }
        return $processed;
    }
}