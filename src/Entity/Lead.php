<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Source;
use App\Entity\Status;
use App\Entity\User;
use App\Entity\History;
use App\Entity\Document;
use App\Entity\Quotation;
use App\Entity\Call;
use App\Entity\Task;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'leads')]
class Lead
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100)]
    private string $first_name;

    #[ORM\Column(type: 'string', length: 100)]
    private string $last_name;

    #[ORM\Column(type: 'string', length: 150, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $phone = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $city = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $address = null;

    #[ORM\Column(type: 'integer', length: 5)]
    private ?string $zip = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $country = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $created_at;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $updated_at = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "leads")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
    private ?User $assigned_user = null;

    #[ORM\ManyToOne(targetEntity: Source::class, inversedBy: "leads")]
    #[ORM\JoinColumn(name: "source_id", referencedColumnName: "id", nullable: false)]
    private Source $source;

    #[ORM\ManyToOne(targetEntity: Status::class, inversedBy: "leads")]
    #[ORM\JoinColumn(name: "status_id", referencedColumnName: "id", nullable: false)]
    private Status $status;

    #[ORM\OneToMany(mappedBy: "lead", targetEntity: Quotation::class)]
    private $quotations;

    #[ORM\OneToMany(mappedBy: "lead", targetEntity: History::class)]
    private $histories;

    #[ORM\OneToMany(mappedBy: "lead", targetEntity: Document::class)]
    private $documents;

    #[ORM\OneToMany(mappedBy: "lead", targetEntity: Task::class)]
    private $tasks;

    #[ORM\OneToMany(mappedBy: "lead", targetEntity: Call::class)]
    private $calls;

    public function getQuotations()
    {
        return $this->quotations;
    }
    
    public function getHistories()
    {
        return $this->histories;
    }

    public function getDocuments()
    {
        return $this->documents;
    }

    public function getTasks()
    {
        return $this->tasks;
    }

    public function getCalls()
    {
        return $this->calls;
    }

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes = null;

    public function __construct()
    {
        $this->quotations = new ArrayCollection();
        $this->histories = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->calls = new ArrayCollection();
        $this->created_at = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(?string $zip): void
    {
        $this->zip = $zip;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    public function getSource(): Source
    {
        return $this->source;
    }
    
    public function setSource(Source $source): void
    {
        $this->source = $source;
    }
    
    public function getStatus(): Status
    {
        return $this->status;
    }
    
    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function getAssignedUser(): ?User
    {
        return $this->assigned_user;
    }

    public function setAssignedUser(?User $assigned_user): void
    {
        $this->assigned_user = $assigned_user;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }
}
