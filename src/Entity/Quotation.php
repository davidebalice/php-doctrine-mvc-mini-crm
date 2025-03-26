<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'quotations')]
class Quotation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Lead::class, inversedBy: 'quotations')]
    #[ORM\JoinColumn(name: 'lead_id', referencedColumnName: 'id', nullable: false)]
    private Lead $lead;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'string', length: 255)]
    private string $code;

    #[ORM\Column(type: 'string', length: 255)]
    private string $status;

    #[ORM\OneToMany(mappedBy: 'quotation', targetEntity: QuotationItem::class, cascade: ['persist', 'remove'])]
    private Collection $items;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $updated_at = null;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->created_at = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLead(): Lead
    {
        return $this->lead;
    }

    public function setLead(Lead $lead): void
    {
        $this->lead = $lead;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function getTotal(): float
    {
        $total = 0.0;

        foreach ($this->items as $item) {
            $total += $item->getPrice();
        }

        return $total;
    }
}