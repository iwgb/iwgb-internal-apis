<?php

namespace Iwgb\Internal\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="t_Invoice")
 */
class Invoice {
    /**
     * @ORM\Id
     * @ORM\Column
     */
    public string $id;

    /**
     * @ORM\Column
     */
    public string $market;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $hash;

    /**
     * @ORM\Column
     */
    public string $area;

    /**
     * @ORM\Column
     */
    public string $status;

    /**
     * @ORM\Column
     */
    public string $courierId;

    /**
     * @ORM\Column
     */
    public string $vehicle;

    /**
     * @ORM\Column(type="text", length=65535)
     */
    public string $data;

    /**
     * @param string $id
     * @return Invoice
     */
    public function setId(string $id): Invoice {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $market
     * @return Invoice
     */
    public function setMarket(string $market): Invoice {
        $this->market = $market;
        return $this;
    }

    /**
     * @param string|null $hash
     * @return Invoice
     */
    public function setHash(?string $hash): Invoice {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @param string $area
     * @return Invoice
     */
    public function setArea(string $area): Invoice {
        $this->area = $area;
        return $this;
    }

    /**
     * @param string $status
     * @return Invoice
     */
    public function setStatus(string $status): Invoice {
        $this->status = $status;
        return $this;
    }

    /**
     * @param string $courierId
     * @return Invoice
     */
    public function setCourierId(string $courierId): Invoice {
        $this->courierId = $courierId;
        return $this;
    }

    /**
     * @param string $vehicle
     * @return Invoice
     */
    public function setVehicle(string $vehicle): Invoice {
        $this->vehicle = $vehicle;
        return $this;
    }

    /**
     * @param string $data
     * @return Invoice
     */
    public function setData(string $data): Invoice {
        $this->data = $data;
        return $this;
    }
}