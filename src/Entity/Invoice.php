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
    public function getData(): string {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData(string $data): void {
        $this->data = $data;
    }
}