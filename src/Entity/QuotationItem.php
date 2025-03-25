<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'quotation_items')]
class QuotationItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Quotation::class, inversedBy: 'items')]
    #[ORM\JoinColumn(name: 'quotation_id', referencedColumnName: 'id', nullable: false)]
    private Quotation $quotation;

    #[ORM\Column(type: 'string', length: 255)]
    private string $service_name;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $price;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    public function getId(): int
    {
        return $this->id;
    }

    public function getQuotation(): Quotation
    {
        return $this->quotation;
    }

    public function setQuotation(Quotation $quotation): void
    {
        $this->quotation = $quotation;
    }

    public function getServiceName(): string
    {
        return $this->service_name;
    }

    public function setServiceName(string $service_name): void
    {
        $this->service_name = $service_name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getSubtotal(): float
    {
        return $this->price*$this->quantity;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
