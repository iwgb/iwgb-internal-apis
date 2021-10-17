<?php

namespace Iwgb\Internal\Unwrapped\Handler;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Iwgb\Internal\Entity\Invoice;
use Iwgb\Internal\HttpCompatibleException;
use Iwgb\Internal\Unwrapped\Dto\SaveInvoiceDataDto;
use Siler\Http\Request;
use Siler\Http\Response;
use Teapot\StatusCode;

class SaveInvoiceData extends AbstractPersistingHandler {

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

        $data = SaveInvoiceDataDto::fromRequest();

        foreach ($data->invoices as $invoiceData) {
            if (
                !empty($invoiceData->hash)
                && $this->hashExistsInDatabase($invoiceData->hash)
            ) {
                $this->s3->deleteObject([
                    'Bucket' => $this->bucket,
                    'Key' => self::BUCKET_PREFIX . self::getInvoiceFilename($data->courierId, $invoiceData->id),
                ]);
                continue;
            }
            $this->em->persist($invoiceData->toEntity($data));
        }
        $this->em->flush();

        Response\no_content();
    }

    private function hashExistsInDatabase(string $hash): bool {
        return $this->em->getRepository(Invoice::class)
                ->findOneBy(['hash' => $hash]) !== null;
    }
}