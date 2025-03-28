<?php

namespace App\Controllers;

use App\Entity\Lead;
use App\Entity\History;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Service\HistoryService;

class HistorysController extends RenderController
{
    private $entityManager;
    private $historyService;

    public function __construct(EntityManagerInterface $entityManager, HistoryService $historyService)
    {
        $this->entityManager = $entityManager;
        $this->historyService = $historyService;
        parent::__construct($entityManager,$historyService);
    }

    //metodo per inviare risposta in json
    private function jsonResponse($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    // Ottieni tutti i histories
    public function histories($id)
    {
        // Verifica che l'ID sia valido
        $leadId = filter_var($id, FILTER_VALIDATE_INT);
        if (!$leadId || $leadId <= 0) {
            die("ID Lead non valido.");
        }

        $lead = $this->entityManager->getRepository(Lead::class)->find($leadId);
        
        $page = isset($_GET['page']) ? filter_var($_GET['page'], FILTER_VALIDATE_INT) : 1;
        $page = $page && $page > 0 ? $page : 1; // Controllo sulla variabile get page
        $limit = 12;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Impostiamo un limite alla lunghezza della ricerca per evitare query pesanti
        if (strlen($search) > 255) {
            $search = substr($search, 0, 255);
        }

        // Sanifica il valore per prevenire attacchi XSS
        $search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8');

        // Query per ottenere le chiamate del lead specifico
        $queryBuilder = $this->entityManager->getRepository(History::class)->createQueryBuilder('c')
            ->where('c.lead = :lead')
            ->setParameter('lead', $lead);

        /*
        if (!empty($search)) {
            $queryBuilder->andWhere('c.name LIKE :search')
                ->setParameter('search', "%$search%");
        }
        */

        $query = $queryBuilder
            ->orderBy('c.due_date', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);
        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / $limit);

        $data = [
            'title' => 'Historys',
            'description' => 'View histories of lead',
            'lead' => $lead,
            'histories' => $paginator,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ];

        $this->render('/leads/histories', $data);
    }

    public function detail($id) {
        $history = $this->entityManager->getRepository(History::class)->find($id);

        $data = [
            'due_date' => $history->getDueDate(),
            'status' => $history->getStatus(),
            'description' => $history->getDescription(),
        ];

        return $this->jsonResponse($data);
    }

    public function store(Request $request) {
        $lead_id = trim($request->request->get('lead_id', ''));

        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/leads/histories/$lead_id';</script>";
            exit();
        }

        // Recupero e sanificazione dei dati inviati dal form
        $due_date = trim($request->request->get('due_date', ''));
        $status = trim($request->request->get('status', ''));
        $description = trim($request->request->get('description', ''));
        $lead_id = trim($request->request->get('lead_id', ''));


        $lead = $this->entityManager->getRepository(Lead::class)->find($lead_id);
        if (!$lead) {
            $_SESSION['error'] = "Lead not found";
            header('Location: /leads');
            exit();
        }
        
        // Sanificazione dei dati
        $due_date = htmlspecialchars($due_date, ENT_QUOTES, 'UTF-8');
        $status = htmlspecialchars($status, ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');

        // Verifica se i campi obbligatori sono vuoti
        if (empty($due_date) || empty($description) || empty($status)) {
            $_SESSION['error'] = "All fields are mandatory";
            header('Location: /leads');
            exit();
        }

        // Crea un nuovo Lead e imposta i dati
        $history = new History();
        $due_date = new \DateTime($due_date);
        $history->setDueDate($due_date);
        $history->setStatus($status);
        $history->setDescription($description);
        $history->setLead($lead);

        $this->entityManager->persist($history);
        $this->entityManager->flush();

        $this->historyService->logAction('Create new history', 'History');

        header('Location: /leads/histories/'.$lead_id);
        exit();
    }

    public function edit($id) {
        $history = $this->entityManager->getRepository(History::class)->find($id);

        $data = [
            'due_date' => $history->getDueDate(),
            'status' => $history->getStatus(),
            'description' => $history->getDescription(),
        ];

        return $this->jsonResponse($data);
    }

    public function update(Request $request)
    {
        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/leads';</script>";
            exit();
        }

        $id = $request->request->get('history_id');
        $id = (int) $id; // Conversione sicura a intero

        $history = $this->entityManager->getRepository(History::class)->find($id);

        if (!$history) {
            die('history non found');
        }

        // Recupero e sanificazione dei dati inviati dal form
        $due_date = trim($request->request->get('due_date', ''));
        $status = trim($request->request->get('status', ''));
        $description = trim($request->request->get('description', ''));
        $lead_id = trim($request->request->get('lead_id', ''));
  
        
        // Sanificazione dei dati
        $due_date = htmlspecialchars($due_date, ENT_QUOTES, 'UTF-8');
        $status = htmlspecialchars($status, ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');


        // Verifica se i campi obbligatori sono vuoti
        if (empty($due_date) || empty($status) || empty($description) ) {
            $_SESSION['error'] = "All fields are mandatory";
            header('Location: /leads/histories/'.$lead_id);
            exit();
        }

        $due_date = new \DateTime($due_date);
        $history->setDueDate($due_date);
        $history->setStatus($status);
        $history->setDescription($description);
      

        $this->entityManager->flush();

        header('Location: /leads/histories/'.$lead_id);
        exit();
    }

    public function delete($lead_id,$id)
    {
        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/leads/histories/$lead_id';</script>";
            exit();
        }

        $history = $this->entityManager->getRepository(History::class)->find($id);
    
        if ($history) {
            $this->entityManager->remove($history);
            $this->entityManager->flush();
        }
    
        header('Location: /leads/histories/'.$lead_id);
        exit();
    }
}
