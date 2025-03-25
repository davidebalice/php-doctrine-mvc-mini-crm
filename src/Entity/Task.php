<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tasks')]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $description;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $due_date;

    #[ORM\Column(type: 'string', length: 50)]
    private string $status;

    #[ORM\ManyToOne(targetEntity: Lead::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(name: 'lead_id', referencedColumnName: 'id')]
    private Lead $lead;

    public function __construct()
    {
        $this->due_date = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getShortDescription(int $length = 70): string
    {
        return strlen($this->description) > $length ? substr($this->description, 0, $length) . '...' : $this->description;
    }

    public function getDueDate(): \DateTime
    {
        return $this->due_date;
    }

    public function getDueDateClass(): string
    {
        $today = new \DateTime();
        
        // Se la data di scadenza è passata, restituisce la classe "text-red"
        return $this->due_date < $today ? 'text-red' : '';
    }

    public function setDueDate(\DateTime $due_date): void
    {
        $this->due_date = $due_date;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
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
