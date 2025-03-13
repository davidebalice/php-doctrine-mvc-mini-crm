<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'calls')]
class Call
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $call_time;

    #[ORM\Column(type: 'string', length: 100)]
    private string $status; // Ad esempio, "Completed", "Missed", ecc.

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "calls")]
    #[ORM\JoinColumn(name: "lead_id", referencedColumnName: "id")]
    private Lead $lead;

    public function __construct()
    {
        $this->call_time = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCallTime(): \DateTime
    {
        return $this->call_time;
    }

    public function setCallTime(\DateTime $call_time): void
    {
        $this->call_time = $call_time;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }

    public function getLead(): Lead
    {
        return $this->lead;
    }

    public function setLead(Lead $lead): void
    {
        $this->lead = $lead;
    }
}
