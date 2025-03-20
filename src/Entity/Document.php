<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'documents')]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $filename;

    #[ORM\Column(type: 'string', length: 255)]
    private string $path;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $uploaded_at;

    #[ORM\ManyToOne(targetEntity: Lead::class, inversedBy: 'documents')]
    #[ORM\JoinColumn(name: 'lead_id', referencedColumnName: 'id')]
    private Lead $lead;

    public function __construct()
    {
        $this->uploaded_at = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getUploadedAt(): \DateTime
    {
        return $this->uploaded_at;
    }

    public function setUploadedAt(\DateTime $uploaded_at): void
    {
        $this->uploaded_at = $uploaded_at;
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
