<?php

namespace Iwgb\Internal\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Invoice {

    /**
     * @ORM\Id
     * @ORM\Column
     */
    protected string $id;

    /**
     * @ORM\Column(nullable=true)
     */
    protected ?string $hash;

    /**
     * @ORM\Column
     */
    protected string $zone;

    /**
     * @ORM\Column
     */
    protected string $status;

    /**
     * @ORM\Column
     */
    protected string $riderId;

    /**
     * @ORM\Column(type="text", length=65535)
     */
    protected string $data;

    public function __construct(string $id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getHash(): ?string {
        return $this->hash;
    }

    /**
     * @param string|null $hash
     */
    public function setHash(?string $hash): void {
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getZone(): string {
        return $this->zone;
    }

    /**
     * @return string
     */
    public function getStatus(): string {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void {
        $this->status = $status;
    }

    /**
     * @param string $zone
     */
    public function setZone(string $zone): void {
        $this->zone = $zone;
    }

    /**
     * @return string
     */
    public function getData(): string {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData(string $data): void {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getRiderId(): string {
        return $this->riderId;
    }

    /**
     * @param string $riderId
     */
    public function setRiderId(string $riderId): void {
        $this->riderId = $riderId;
    }
}