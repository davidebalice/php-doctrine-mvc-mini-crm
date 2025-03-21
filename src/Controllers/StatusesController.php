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
            'description' => 'View of all statuses',
            'statuses' => $paginator,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ];

        $this->render('/statuses/statuses', $data);
    }

    public function create() {
        $data = [
            'title' => 'New status',
            'description' => 'Create new status',
        ];

        $this->render('/statuses/new', $data);
    }

    public function store(Request $request) {
        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/statuses';</script>";
            exit();
        }

        $name = trim($request->request->get('name', ''));
        
        // Sanificazione
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        
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

    public function edit($id) {
        $status = $this->entityManager->getRepository(Status::class)->find($id);

        $data = [
            'title' => 'Edit status',
            'description' => 'Edit status',
            'status' => $status,
        ];

        $this->render('/statuses/edit', $data);
    }

    public function update(Request $request)
    {
        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/statuses';</script>";
            exit();
        }

        $id = $request->request->get('id');
        $id = (int) $id; // Conversione sicura a intero
       
        $status = $this->entityManager->getRepository(Status::class)->find($id);

        if (!$status) {
            die('Status non found');
        }

        // Sanificazione
        $name = htmlspecialchars($request->request->get('name'), ENT_QUOTES, 'UTF-8');

        $status->setName($name);

        $this->entityManager->flush();

        header('Location: /statuses');
        exit();
    }

    public function delete($id)
    {
        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/statuses';</script>";
            exit();
        }
        $status = $this->entityManager->getRepository(Status::class)->find($id);
    
        if ($status) {
            $this->entityManager->remove($status);
            $this->entityManager->flush();
        }
    
        header('Location: /statuses');
        exit();
    }
}
