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

    public function statuses()
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // Ottieni la pagina dalla query string
        $limit = 12; // Numero di elementi per pagina
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

   // Pagina nuovo status
   public function create() {
        $data = [
            'title' => 'New status',
            'description' => 'Create new status',
        ];

        $this->render('/statuses/new', $data);
    }

    // Crea un nuovo status
    public function store(Request $request) {
        $status = new Status();
        $status->setName($request->request->get('name'));

        $this->entityManager->persist($status);
        $this->entityManager->flush();

        header('Location: /statuses');
        exit();
    }



    public function delete($id)
    {
        $status = $this->entityManager->getRepository(Status::class)->find($id);
    
        if ($status) {
            $this->entityManager->remove($status);
            $this->entityManager->flush();
        }
    
        header('Location: /statuses');
        exit();
    }





    /*
   


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
