<?php

namespace Iwgb\Internal\Roovolt\Handler;

use Guym4c\Airtable\AirtableApiException;
use Iwgb\Internal\Roovolt\Dto\SaveInvoiceDataDto;
use Iwgb\Internal\Roovolt\Table;
use JsonSerializable;
use Siler\Http\Response;

class SaveInvoiceData extends AbstractInvoiceStoreHandler {

    /**
     * {@inheritdoc}
     * @throws AirtableApiException
     */
    public function __invoke(array $args): void {
        $data = new SaveInvoiceDataDto();

        $invoicesData = [];
        $invoices = [];
        foreach ($data->invoices as $i => $invoice) {
            if (
                $invoice->status === 'success'
                && $this->airtable->find(Table::INVOICES, 'Hash', $invoice->hash) !== []
            ) {
                $this->store->deleteObject([
                    'Bucket' => $this->bucket,
                    'Key'    => self::BUCKET_PREFIX . self::getInvoiceFilename($data->riderId, $invoice->id),
                ]);
                continue;
            }
            $invoiceData = $invoice->toArray($data);
            $invoicesData[] = array_merge(
                $invoice->toArray($data),
                ['Status' => $invoiceData['Status'] === 'success' ? 'pending' : 'failed'],
            );
            $invoices[$invoice->id] = [
                'invoice' => $invoiceData,
                'shifts' => self::serializeAll($invoice->shifts),
                'adjustments' => self::serializeAll($invoice->adjustments),
            ];
        }
        $invoiceRecords = $this->airtable->createAll(Table::INVOICES, $invoicesData);

        foreach ($invoiceRecords as $invoiceRecord) {
            $this->redis->set($invoiceRecord->getId(), json_encode(
                $invoices[$invoiceRecord->{'Invoice ID'}]
            ));
        }

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