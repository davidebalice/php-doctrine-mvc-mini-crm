<?php

namespace App\Controllers;

use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;

class StatusesController extends RenderController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager);
    }
/*
    // Ottieni tutti gli status
    public function statuses()
    {
        $statuses = $this->entityManager->getRepository(Status::class)->findAll();

        $data = [
            'title' => 'Statuses',
            'description' => 'Lista di tutte gli status',
            'statuses' => $statuses
        ];
        
        $this->render('/statuses/statuses', $data);
    }

*/







  

    public function statuses()
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // Ottieni la pagina dalla query string
        $limit = 2; // Numero di elementi per pagina
        $offset = ($page - 1) * $limit; // Calcola l'offset
    
        $query = $this->entityManager->getRepository(Status::class)
            ->createQueryBuilder('s')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();
    
        $paginator = new Paginator($query);
        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / $limit);
    
        $data = [
            'title' => 'Statuses',
            'description' => 'Lista di tutte gli status',
            'statuses' => $paginator,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ];
    
        $this->render('/statuses/statuses', $data);
    }
















    /*
    // Crea un nuovo lead
    public function store(Request $request)
    {
        $lead = new Lead();
        $lead->setName($request->request->get('name'));
        $lead->setEmail($request->request->get('email'));
        $lead->setPhone($request->request->get('phone'));

        $this->entityManager->persist($lead);
        $this->entityManager->flush();

        header('Location: /leads'); // Reindirizza alla lista leads
        exit();
    }

    // Modifica un lead esistente
    public function update(Request $request, $id)
    {
        $lead = $this->entityManager->getRepository(Lead::class)->find($id);

        if (!$lead) {
            die('Lead non trovato');
        }

        $lead->setName($request->request->get('name'));
        $lead->setEmail($request->request->get('email'));
        $lead->setPhone($request->request->get('phone'));

        $this->entityManager->flush();

        header('Location: /leads');
        exit();
    }

    // Cancella un lead
    public function delete($id)
    {
        $lead = $this->entityManager->getRepository(Lead::class)->find($id);

        if (!$lead) {
            die('Lead non trovato');
        }

        $this->entityManager->remove($lead);
        $this->entityManager->flush();

        header('Location: /leads');
        exit();
    }
        */
}
