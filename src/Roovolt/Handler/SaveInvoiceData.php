<?php

namespace Iwgb\Internal\Roovolt\Handler;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Iwgb\Internal\Entity\Invoice;
use Iwgb\Internal\HttpCompatibleException;
use Iwgb\Internal\Provider\Provider;
use Iwgb\Internal\Roovolt\Dto\SaveInvoiceDataDto;
use Pimple\Container;
use Siler\Http\Request;
use Siler\Http\Response;
use Teapot\StatusCode;

class SaveInvoiceData extends RootHandler {

    private EntityManager $em;

    public function __construct(Container $c) {
        parent::__construct($c);

        $this->em = $c[Provider::DATABASE];
    }

    /**
     * {@inheritdoc}
     * @param array $args
     * @throws HttpCompatibleException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function __invoke(array $args): void {
        if (Request\get('key') !== $this->settings['api']['key']) {
            throw new HttpCompatibleException(
                self::INVALID_KEY_ERROR,
                StatusCode::FORBIDDEN,
            );
        }

        $data = new SaveInvoiceDataDto();

        foreach ($data->invoices as $invoiceData) {
            if (
                !empty($invoiceData->hash)
                && $this->hashExistsInDatabase($invoiceData->hash)
            ) {
                $this->store->deleteObject([
                    'Bucket' => $this->bucket,
                    'Key' => self::BUCKET_PREFIX . self::getInvoiceFilename($data->riderId, $invoiceData->id),
                ]);
                continue;
            }

            $invoice = (new Invoice($invoiceData->id));
            $invoice->setHash($invoiceData->hash);
            $invoice->setZone($data->zone);
            $invoice->setData(json_encode($invoiceData->serialize($data)));
            $this->em->persist($invoice);
        }
        $this->em->flush();

        Response\no_content();
    }

    private function hashExistsInDatabase(string $hash): bool {
        return $this->em->getRepository(Invoice::class)
            ->findOneBy(['hash' => $hash]) !== null;
    }
}