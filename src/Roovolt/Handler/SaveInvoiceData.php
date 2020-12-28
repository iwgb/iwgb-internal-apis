<?php

namespace Iwgb\Internal\Roovolt\Handler;

use Guym4c\Airtable\Airtable;
use Guym4c\Airtable\AirtableApiException;
use Iwgb\Internal\Provider\Provider;
use Iwgb\Internal\Roovolt\Dto\SaveInvoiceDataDto;
use Iwgb\Internal\Roovolt\Table;
use JsonSerializable;
use Pimple\Container;
use Siler\Http\Response;

class SaveInvoiceData extends RootHandler {

    private Airtable $airtable;

    public function __construct(Container $c) {
        parent::__construct($c);

        $this->airtable = $c[Provider::ROOVOLT_AIRTABLE];
    }

    /**
     * @param array $args
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
            $invoicesData[] = $invoice->toArray($data);
            $invoices[$invoice->id] = $invoice;
        }
        $invoiceRecords = $this->airtable->createAll(Table::INVOICES, $invoicesData);

        foreach ($invoiceRecords as $invoiceRecord) {
            $invoice = $invoices[$invoiceRecord->{'Invoice ID'}];
            $this->createChildren(
                Table::SHIFTS,
                $invoice->shifts,
                $invoiceRecord->getId(),
            );
            $this->createChildren(
                Table::ADJUSTMENTS,
                $invoice->adjustments,
                $invoiceRecord->getId(),
            );
        }

        self::withCors();
        Response\no_content();
    }

    /**
     * @param string $table
     * @param JsonSerializable[] $items
     * @param string $invoiceId
     * @throws AirtableApiException
     */
    private function createChildren(string $table, array $items, string $invoiceId): void {
        $data = [];
        foreach ($items as $item) {
            $data[] = array_merge(
                $item->jsonSerialize(),
                ['Invoice' => [$invoiceId]],
            );
        }
        if (count($data) > 0) {
            $this->airtable->createAll($table, $data);
        }
    }
}