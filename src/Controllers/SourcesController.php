<?php

namespace App\Controllers;

use App\Entity\Source;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;

class SourcesController extends RenderController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager);
    }

    // Ottieni tutti i sources
    public function sources()
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

        $queryBuilder = $this->entityManager->getRepository(Source::class)->createQueryBuilder('s');

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
            'title' => 'Sources',
            'description' => 'View of all sources',
            'sources' => $paginator,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ];

        $this->render('/sources/sources', $data);
    }

    
    public function create() {
        $data = [
            'title' => 'New source',
            'description' => 'Create new source',
        ];

        $this->render('/sources/new', $data);
    }

    public function store(Request $request) {
        $name = trim($request->request->get('name', ''));
        
        // Sanificazione
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        
        if (empty($name)) {
            $_SESSION['error'] = "Name field is mandatory";
            header('Location: /sources/create');
            exit();
        }

        $source = new Source();
        $source->setName($name);

        $this->entityManager->persist($source);
        $this->entityManager->flush();

        header('Location: /sources');
        exit();
    }

    public function edit($id) {
        $source = $this->entityManager->getRepository(Source::class)->find($id);

        $data = [
            'title' => 'New source',
            'description' => 'Create new source',
            'source' => $source,
        ];

        $this->render('/sources/edit', $data);
    }

    public function update(Request $request)
    {
        $id = $request->request->get('id');
        $id = (int) $id; // Conversione sicura a intero
       
        $source = $this->entityManager->getRepository(Source::class)->find($id);

        if (!$source) {
            die('Source non found');
        }

        // Sanificazione
        $name = htmlspecialchars($request->request->get('name'), ENT_QUOTES, 'UTF-8');

        $source->setName($name);

        $this->entityManager->flush();

        header('Location: /sources');
        exit();
    }

    public function delete($id)
    {
        $source = $this->entityManager->getRepository(Source::class)->find($id);
    
        if ($source) {
            $this->entityManager->remove($source);
            $this->entityManager->flush();
        }
    
        header('Location: /sources');
        exit();
    }
}
