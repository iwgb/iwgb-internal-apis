<?php

namespace Iwgb\Internal\Roovolt\Dto;

use Iwgb\Internal\HttpCompatibleException;
use JsonSerializable;
use Siler\Http\Request;

class SignUpDto extends AbstractDto implements JsonSerializable {

    public string $name;

    public string $email;

    public string $phone;

    public string $zone;

    /**
     * SaveInvoiceDataDto constructor.
     * @throws HttpCompatibleException
     */
    public function __construct() {
        $data = Request\json();
        parent::__construct($data);

        $this->name = $this->required('name');
        $this->email = $this->required('email');
        $this->phone = $this->required('phone');
        $this->zone = $this->required('zone');

        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        $this->phone = filter_var($this->phone, FILTER_SANITIZE_NUMBER_INT);
    }

    public function jsonSerialize(): array {
        return [
            'Name' => $this->name,
            'Email' => $this->email,
            'Phone' => $this->phone,
            'Zone' => $this->zone,
        ];
    }
}