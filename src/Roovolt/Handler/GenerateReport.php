<?php

namespace Iwgb\Internal\Roovolt\Handler;

use Iwgb\Internal\Entity\LegacyInvoice;
use Iwgb\Internal\HttpCompatibleException;
use League\Csv;
use PhpZip\ZipFile;
use Pimple\Container;
use Siler\Http\Request;
use Siler\Http\Response;
use Teapot\StatusCode;

class GenerateReport extends AbstractPersistingHandler {

    private const INVOICE_FIELDS = ['id', 'status', 'start', 'end', 'zone', 'hash', 'vehicle', 'link'];
    private const SHIFT_FIELDS = ['Start', 'End', 'Orders', 'Pay', 'invoice'];
    private const ADJUSTMENT_FIELDS = ['Label', 'Amount', 'invoice'];

    private string $reportId;

    public function __construct(Container $c) {
        parent::__construct($c);

        $this->reportId = uniqid();
    }

    public function __invoke(array $args): void {
        if (Request\get('key') !== $this->settings['roovolt']['invoiceKey']) {
            throw new HttpCompatibleException(
                self::INVALID_KEY_ERROR,
                StatusCode::FORBIDDEN,
            );
        }

        $invoices = [];
        $shifts = [];
        $adjustments = [];
        foreach ($this->em->getRepository(LegacyInvoice::class)->findAll() as $invoiceEntity) {
            /** @var $invoiceEntity LegacyInvoice */
            $invoice = json_decode($invoiceEntity->getData(), true);
            $invoices[] = self::getValuesFromHeader(self::INVOICE_FIELDS, $invoice, [
                'https://internal.iwgb.org.uk/roovolt/invoice?' . http_build_query([
                    'riderId' => $invoice['riderId'],
                    'invoiceId' => $invoice['id'],
                    'key' => $this->settings['roovolt']['invoiceKey'],
                ]),
            ]);
            foreach ($invoice['shifts'] as $shift) {
                $shifts[] = self::getValuesFromHeader(self::SHIFT_FIELDS, $shift, [$invoice['id']]);
            }
            foreach ($invoice['adjustments'] as $adjustment) {
                $adjustments[] = self::getValuesFromHeader(self::ADJUSTMENT_FIELDS, $adjustment, [$invoice['id']]);
            }
        }

        mkdir(APP_ROOT . "/var/report/{$this->reportId}", 0755, true);
        $this->writeCsv(self::INVOICE_FIELDS, $invoices, 'invoices');
        $this->writeCsv(self::SHIFT_FIELDS, $shifts, 'shifts');
        $this->writeCsv(self::ADJUSTMENT_FIELDS, $adjustments, 'adjustments');

        $archivePath = APP_ROOT . "/var/report/{$this->reportId}/{$this->reportId}.zip";
        (new ZipFile())->addDir(APP_ROOT . "/var/report/{$this->reportId}")
            ->saveAsFile($archivePath)
            ->close();

        Response\header('content-type', 'application/zip, application/octet-stream');
        Response\header('cache-control', 'no-store');
        Response\header('content-length', filesize($archivePath));
        Response\header('content-disposition', "attachment; filename=\"report-{$this->reportId}.zip\"");

        $file = fopen($archivePath, 'r');
        fpassthru($file);

        $this->cleanUpTempDir(['invoices', 'shifts', 'adjustments']);
    }

    private function writeCsv(array $header, array $data, string $filename): Csv\Writer {
        $csv = Csv\Writer::createFromPath(APP_ROOT . "/var/report/{$this->reportId}/{$filename}.csv", 'x+');
        $csv->insertOne($header);
        $csv->insertAll($data);
        return $csv;
    }

    private static function getValuesFromHeader(array $header, array $json, array $append): array {
        $values = [];
        foreach ($header as $field) {
            $values[] = $json[$field] ?? '';
        }
        return array_merge(array_slice($values, 0, count($append) * -1), $append);
    }

    private function cleanUpTempDir(array $csvs): void {
        $rootDir = APP_ROOT . "/var/report/{$this->reportId}";
        foreach ($csvs as $csv) {
            unlink("{$rootDir}/{$csv}.csv");
        }
        unlink("{$rootDir}/{$this->reportId}.zip");
        rmdir($rootDir);
    }
}