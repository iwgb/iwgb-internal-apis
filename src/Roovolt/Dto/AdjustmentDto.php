<?php

namespace Iwgb\Internal\Roovolt\Dto;

use Iwgb\Internal\HttpCompatibleException;

class AdjustmentDto extends AbstractDto {

    public string $label;

    public float $amount;

    /**
     * AdjustmentDto constructor.
     * @param array $data
     * @throws HttpCompatibleException
     */
    public function __construct(array $data) {
        parent::__construct($data);

        $this->label = $this->required('label');
        $this->amount = $this->required('amount');
    }

    public function toArray(): array {
        return [
            'Label' => $this->label,
            'Amount' => $this->amount,
        ];
    }
}