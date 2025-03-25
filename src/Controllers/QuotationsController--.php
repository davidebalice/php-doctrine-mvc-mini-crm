<?php

namespace App\Controllers;

use App\Entity\Quotation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;
class QuotationsController extends RenderController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager);
    }

    // Ottieni tutti i preventivi
    public function quotations()
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

        $queryBuilder = $this->entityManager->getRepository(Quotation::class)->createQueryBuilder('s');

        if (!empty($search)) {
            $queryBuilder->where('s.name LIKE :search')
                        ->setParameter('search', "%$search%");
        }

        $query = $queryBuilder
            ->orderBy('s.created_at', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);
        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / $limit);

        $data = [
            'title' => 'Quotations',
            'description' => 'View of all quotations',
            'quotations' => $paginator,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ];

        $this->render('/quotations/quotations', $data);
    }

    public function detail($id) {
        $quotation = $this->entityManager->getRepository(Quotation::class)->find($id);

        $data = [
            'title' => 'quotations details',
            'description' => 'Quotation details',
            'quotation' => $quotation,
        ];

        $this->render('/quotations/detail', $data);
    }

    public function create() {
        $data = [
            'title' => 'New quotation',
            'description' => 'Create new quotation',
        ];

        $this->render('/quotations/new', $data);
    }

    public function store(Request $request) {
        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/quotations';</script>";
            exit();
        }

        $name = trim($request->request->get('name', ''));
        
        // Sanificazione
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        
        if (empty($name)) {
            $_SESSION['error'] = "Name field is mandatory";
            header('Location: /quotations/create');
            exit();
        }

        $quotation = new Quotation();
        $quotation->setTitle($name);

        $this->entityManager->persist($quotation);
        $this->entityManager->flush();

        header('Location: /quotations');
        exit();
    }

    public function edit($id) {
        $quotation = $this->entityManager->getRepository(Quotation::class)->find($id);

        $data = [
            'title' => 'New quotation',
            'description' => 'Create new quotation',
            'quotation' => $quotation,
        ];

        $this->render('/quotations/edit', $data);
    }

    public function update(Request $request)
    {
        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/quotations';</script>";
            exit();
        }

        $id = $request->request->get('id');
        $id = (int) $id; // Conversione sicura a intero
       
        $quotation = $this->entityManager->getRepository(Quotation::class)->find($id);

        if (!$quotation) {
            die('Quotation non found');
        }

        // Sanificazione
        $name = htmlspecialchars($request->request->get('name'), ENT_QUOTES, 'UTF-8');

        $quotation->setName($name);

        $this->entityManager->flush();

        header('Location: /quotations');
        exit();
    }

    public function delete($id)
    {
        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed');
            window.location.href='/quotations';</script>";
            exit();
        }

        $quotation = $this->entityManager->getRepository(Quotation::class)->find($id);
    
        if ($quotation) {
            $this->entityManager->remove($quotation);
            $this->entityManager->flush();
        }
    
        header('Location: /quotations');
        exit();
    }

}
