<?php
namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\History;

class HistoryService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function logAction(string $event, string $type): void
    {
        $history = new History();
        $history->setEvent($event);
        $history->setType($type);
        $history->setCreatedAt(new \DateTime());

        $this->entityManager->persist($history);
        $this->entityManager->flush();
    }
}
