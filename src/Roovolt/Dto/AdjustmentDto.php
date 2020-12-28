<?php

namespace Iwgb\Internal\Roovolt\Dto;

use Iwgb\Internal\HttpCompatibleException;
use JsonSerializable;

class AdjustmentDto extends AbstractDto implements JsonSerializable {

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

    public function jsonSerialize(): array {
        return [
            'Label' => $this->label,
            'Amount' => $this->amount,
        ];
    }
}