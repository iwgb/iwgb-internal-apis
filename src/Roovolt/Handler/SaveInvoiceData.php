<?php

namespace Iwgb\Internal\Roovolt\Handler;

use Guym4c\Airtable\AirtableApiException;
use Iwgb\Internal\HttpCompatibleException;
use Iwgb\Internal\Roovolt\Dto\SaveInvoiceDataDto;
use Iwgb\Internal\Roovolt\Table;
use JsonSerializable;
use Siler\Http\Request;
use Siler\Http\Response;
use Teapot\StatusCode;

class SaveInvoiceData extends AbstractInvoiceStoreHandler {

    /**
     * {@inheritdoc}
     * @throws AirtableApiException
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

        $invoicesData = [];
        $invoicesById = [];
        foreach ($data->invoices as $i => $invoice) {
            $invoiceData = $invoice->toArray($data);
            $invoicesData[] = array_merge(
                $invoiceData,
                ['Status' => $invoiceData['Status'] === 'success' ? 'pending' : 'failed'],
            );
            $invoicesById[$invoice->id] = [
                'invoice' => $invoiceData,
                'shifts' => self::serializeAll($invoice->shifts),
                'adjustments' => self::serializeAll($invoice->adjustments),
            ];
        }
        $invoiceRecords = $this->airtable->createAll(Table::INVOICES, $invoicesData);

        $dataToCache = [];
        foreach ($invoiceRecords as $invoiceRecord) {
            $dataToCache[$invoiceRecord->getId()] =  json_encode(
                $invoicesById[$invoiceRecord->{'Invoice ID'}]
            );
        }
        $this->redis->mset($dataToCache);

        self::withCors();
        Response\no_content();
    }

    /**
     * @param JsonSerializable[] $items
     * @return array
     */
    private static function serializeAll(array $items): array {
        $data = [];
        foreach ($items as $item) {
            $data[] = $item->jsonSerialize();
        }
        return $data;
    }
}