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
        $page = isset($_GET['page']) ? filter_var($_GET['page'], FILTER_VALIDATE_INT) : 1;
        $page = $page && $page > 0 ? $page : 1; // Controllo sulla pariagile get page
        $limit = 12;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Impostiamo un limite alla lunghezza della ricerca per evitare query pesanti
        if (strlen($search) > 255) {
            $search = substr($search, 0, 255);
        }

         // Sanifica il valore per prevenire attacchi XSS
        $search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8');

        $queryBuilder = $this->entityManager->getRepository(Status::class)->createQueryBuilder('s');

        if (!empty($search)) {
            $queryBuilder->where('s.name LIKE :search')
                        ->setParameter('search', "%$search%");
        }

        $query = $queryBuilder
            ->orderBy('s.name', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);
        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / $limit);

        $data = [
            'title' => 'Statuses',
            'description' => 'Lista di tutti gli status',
            'statuses' => $paginator,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
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
        $name = trim($request->request->get('name', ''));
        if (empty($name)) {
            $_SESSION['error'] = "Name field is mandatory";
            header('Location: /statuses/create');
            exit();
        }

        $status = new Status();
        $status->setName($name);

        $this->entityManager->persist($status);
        $this->entityManager->flush();

        header('Location: /statuses');
        exit();
    }

    // Pagina nuovo status
    public function edit($id) {
        $status = $this->entityManager->getRepository(Status::class)->find($id);

        $data = [
            'title' => 'New status',
            'description' => 'Create new status',
            'status' => $status,
        ];

        $this->render('/statuses/edit', $data);
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
