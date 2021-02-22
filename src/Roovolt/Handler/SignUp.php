<?php

namespace Iwgb\Internal\Roovolt\Handler;

use Guym4c\Airtable\Airtable;
use Guym4c\Airtable\AirtableApiException;
use Iwgb\Internal\Provider\Provider;
use Iwgb\Internal\Roovolt\Dto\SignUpDto;
use Pimple\Container;

class SignUp extends RootHandler {

    private Airtable $airtable;

    public function __construct(Container $c) {
        parent::__construct($c);

        $this->airtable = $c[Provider::ROOVOLT_AIRTABLE];
    }

    /**
     * {@inheritDoc}
     * @throws AirtableApiException
     */
    public function __invoke(array $args): void {
        $data = new SignUpDto();

        $this->airtable->create('Data', $data->jsonSerialize());
    }
}