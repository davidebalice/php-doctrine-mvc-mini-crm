<?php

namespace App\Controllers;

use App\Entity\Lead;
use App\Entity\Source;
use App\Entity\Status;
use App\Entity\History;
use App\Entity\Quotation;
use App\Entity\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;
class LeadsQuotationsController extends RenderController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager);
    }

    //metodo per inviare risposta in json
    private function jsonResponse($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    // Ottieni tutti i preventivi legati al lead
    public function quotations($id)
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
        $queryBuilder = $this->entityManager->getRepository(Quotation::class)->createQueryBuilder('q')
            ->where('q.lead = :lead')
            ->setParameter('lead', $lead);

        /*
        if (!empty($search)) {
            $queryBuilder->andWhere('c.name LIKE :search')
                ->setParameter('search', "%$search%");
        }
        */

        $query = $queryBuilder
            ->orderBy('q.created_at', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);
        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / $limit);

        $data = [
            'title' => 'Quotations',
            'description' => 'View quotations of lead',
            'lead' => $lead,
            'quotations' => $paginator,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ];

        $this->render('/leads/quotations', $data);
    }

    public function create() {
      
        $data = [
            'title' => 'New quotation',
            'description' => 'Create new quotation',
        ];

        $this->render('/leads/quotations_new', $data);
    }

    public function detail($id) {
        $quotation = $this->entityManager->getRepository(Quotation::class)->find($id);

        $data = [
            'due_date' => $quotation->getDueDate(),
            'status' => $quotation->getStatus(),
            'description' => $quotation->getDescription(),
        ];

        return $this->jsonResponse($data);
    }

    public function store(Request $request) {
        $lead_id = trim($request->request->get('lead_id', ''));
    
        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/leads/quotations/$lead_id';</script>";
            exit();
        }
    
        // Recupero e sanificazione dei dati inviati dal form
        $due_date = trim($request->request->get('due_date', ''));
        $status = trim($request->request->get('status', ''));
        $description = trim($request->request->get('description', ''));
        $items = $request->request->all('items'); // Array degli items
    
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
    
        if (empty($due_date) || empty($description) || empty($status)) {
            $_SESSION['error'] = "All fields are mandatory";
            header('Location: /leads');
            exit();
        }
    
        // Creazione della Quotation
        $quotation = new Quotation();
        $quotation->setDueDate(new \DateTime($due_date));
        $quotation->setStatus($status);
        $quotation->setDescription($description);
        $quotation->setLead($lead);
    
        $this->entityManager->persist($quotation);
    
        // Aggiunta degli Items
        foreach ($items as $itemData) {
            if (!isset($itemData['name']) || !isset($itemData['price']) || !isset($itemData['quantity'])) {
                continue;
            }
    
            $item = new Item();
            $item->setName(htmlspecialchars($itemData['name'], ENT_QUOTES, 'UTF-8'));
            $item->setPrice((float)$itemData['price']);
            $item->setQuantity((int)$itemData['quantity']);
            $item->setQuotation($quotation); // Associa l'item alla Quotation
    
            $this->entityManager->persist($item);
        }
    
        // Salva tutto nel database
        $this->entityManager->flush();
    
        header('Location: /leads/quotations/'.$lead_id);
        exit();
    }
    

    public function edit($id) {
        $quotation = $this->entityManager->getRepository(Quotation::class)->find($id);

        $data = [
            'due_date' => $quotation->getDueDate(),
            'status' => $quotation->getStatus(),
            'description' => $quotation->getDescription(),
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

        $id = $request->request->get('quotation_id');
        $id = (int) $id; // Conversione sicura a intero

        $quotation = $this->entityManager->getRepository(Quotation::class)->find($id);

        if (!$quotation) {
            die('quotation non found');
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
            header('Location: /leads/quotations/'.$lead_id);
            exit();
        }

        $due_date = new \DateTime($due_date);
        $quotation->setDueDate($due_date);
        $quotation->setStatus($status);
        $quotation->setDescription($description);
      

        $this->entityManager->flush();

        header('Location: /leads/quotations/'.$lead_id);
        exit();
    }

    public function delete($lead_id,$id)
    {
        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/leads/quotations/$lead_id';</script>";
            exit();
        }

        $quotation = $this->entityManager->getRepository(Quotation::class)->find($id);
    
        if ($quotation) {
            $this->entityManager->remove($quotation);
            $this->entityManager->flush();
        }
    
        header('Location: /leads/quotations/'.$lead_id);
        exit();
    }
}
