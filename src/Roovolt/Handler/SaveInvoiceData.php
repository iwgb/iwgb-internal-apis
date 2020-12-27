<?php

namespace Iwgb\Internal\Roovolt\Handler;

use Guym4c\Airtable\Airtable;
use Guym4c\Airtable\AirtableApiException;
use Iwgb\Internal\Provider\Provider;
use Iwgb\Internal\Roovolt\Dto\SaveInvoiceDataDto;
use Iwgb\Internal\Roovolt\Table;
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

        foreach ($data->invoices as $i => $invoice) {
            if (
                $invoice->status === 'success'
                && $this->airtable->find(Table::INVOICES, 'Hash', $invoice->hash) !== []
            ) {
                $this->store->deleteObject([
                    'Bucket' => $this->bucket,
                    'Key' => self::BUCKET_PREFIX . self::getInvoiceFilename($data->riderId, $invoice->id),
                ]);
                unset($data->invoices[$i]);
                continue;
            }

            $invoiceRecord = $this->airtable->create(Table::INVOICES, $invoice->toArray($data));

            foreach ($invoice->shifts as $shift) {
                $this->airtable->create(Table::SHIFTS, array_merge(
                    $shift->toArray(),
                    ['Invoice' => [$invoiceRecord->getId()]],
                ));
            }
            foreach ($invoice->adjustments as $adjustment) {
                $this->airtable->create(TABLE::ADJUSTMENTS, array_merge(
                    $adjustment->toArray(),
                    ['Invoice' => [$invoiceRecord->getId()]],
                ));
            }
        }

        self::withCors();
        Response\no_content();
    }
}