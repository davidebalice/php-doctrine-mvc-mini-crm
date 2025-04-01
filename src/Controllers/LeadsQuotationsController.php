<?php

namespace App\Controllers;

use App\Entity\Lead;
use App\Entity\History;
use App\Entity\Quotation;
use App\Entity\QuotationItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Services\HistoryService;
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

    public function create($id) {
        $lead = $this->entityManager->getRepository(Lead::class)->find($id);

        $data = [
            'title' => 'New quotation',
            'description' => 'Create new quotation',
            'lead_id' => $id,
            'lead' => $lead,
        ];

        $this->render('/leads/quotations_new', $data);
    }

    public function detail($lead_id,$id) {
        $lead = $this->entityManager->getRepository(Lead::class)->find($lead_id);
        $quotation = $this->entityManager->getRepository(Quotation::class)->find($id);

        $data = [
            'lead' => $lead,
            'quotation' => $quotation,
        ];

        $this->render('/leads/quotations_detail', $data);
    }

    public function store(Request $request) {
        $lead_id = trim($request->request->get('lead_id', ''));
    
        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/leads/quotations/$lead_id';</script>";
            exit();
        }
    
        // Recupero e sanificazione dei dati inviati dal form
        //$due_date = trim($request->request->get('due_date', ''));
        $status = trim($request->request->get('status', ''));
        $title = trim($request->request->get('title', ''));
        $code = trim($request->request->get('code', ''));
        $items = $request->request->all('items');
    

        //var_dump($request->request->all()); die;


        $lead = $this->entityManager->getRepository(Lead::class)->find($lead_id);
        if (!$lead) {
            $_SESSION['error'] = "Lead not found";
            header('Location: /leads/quotations/'.$lead_id);
            exit();
        }
    
        // Sanificazione dei dati
        //$due_date = htmlspecialchars($due_date, ENT_QUOTES, 'UTF-8');
        $status = htmlspecialchars($status, ENT_QUOTES, 'UTF-8');
        $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $code = htmlspecialchars($code, ENT_QUOTES, 'UTF-8');
    
        if (empty($title) || empty($code)) {
            $_SESSION['error'] = "All fields are mandatory";
            header('Location: /leads/quotations/'.$lead_id);
            exit();
        }
    
        // Creazione della Quotation
        $quotation = new Quotation();
        //$quotation->setCreatedAt(new \DateTime($due_date));
        $quotation->setStatus($status);
        $quotation->setTitle($title);
        $quotation->setCode($code);
        $quotation->setLead($lead);
    
        $this->entityManager->persist($quotation);
    
        // Aggiunta degli Items
        foreach ($items as $itemData) {
            if (!isset($itemData['service_name']) || !isset($itemData['price']) || !isset($itemData['quantity'])) {
                continue;
            }
    
            $item = new QuotationItem();
            $item->setServiceName(htmlspecialchars($itemData['service_name'], ENT_QUOTES, 'UTF-8'));
            $item->setDescription(htmlspecialchars($itemData['description'], ENT_QUOTES, 'UTF-8'));
            $item->setPrice((float)$itemData['price']);
            $item->setQuantity((int)$itemData['quantity']);
            $item->setQuotation($quotation);
    
            $this->entityManager->persist($item);
        }
    
        // Salva tutto nel database
        $this->entityManager->flush();
    
        header('Location: /leads/quotations/'.$lead_id);
        exit();
    }
    
    public function edit($lead_id,$id) {
        $lead = $this->entityManager->getRepository(Lead::class)->find($lead_id);
        $quotation = $this->entityManager->getRepository(Quotation::class)->find($id);

        $data = [
            'lead' => $lead,
            'quotation' => $quotation,
        ];

        $this->render('/leads/quotations_edit', $data);
    }

    public function update(Request $request)
    {
        $quotationId = trim($request->request->get('id', ''));
        $leadId = trim($request->request->get('lead_id', ''));

        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/leads/quotations/$leadId';</script>";
            exit();
        }

        // Recupera la Quotation dal database
        $quotation = $this->entityManager->getRepository(Quotation::class)->find($quotationId);
        if (!$quotation) {
            $_SESSION['error'] = "Quotation not found";
            header("Location: /leads/quotations/$leadId");
            exit();
        }

        // Recupera e sanifica i dati inviati dal form
        $title = htmlspecialchars(trim($request->request->get('title', '')), ENT_QUOTES, 'UTF-8');
        $code = htmlspecialchars(trim($request->request->get('code', '')), ENT_QUOTES, 'UTF-8');
        $status = htmlspecialchars(trim($request->request->get('status', '')), ENT_QUOTES, 'UTF-8');
        $itemsData = $request->request->all('items'); // Array degli items

        // Verifica se i campi obbligatori sono presenti
        if (empty($title) || empty($code) || empty($status)) {
            $_SESSION['error'] = "All fields are mandatory";
            header("Location: /leads/quotations/$leadId/edit/$quotationId");
            exit();
        }

        // Aggiorna la Quotation
        $quotation->setTitle($title);
        $quotation->setCode($code);
        $quotation->setStatus($status);
        $this->entityManager->persist($quotation);

        // Recupera gli attuali items associati alla Quotation
        $existingItems = $this->entityManager->getRepository(QuotationItem::class)->findBy(['quotation' => $quotation]);
        $existingItemIds = [];

        // Aggiorna o aggiungi nuovi items
        foreach ($itemsData as $itemData) {
            if (!isset($itemData['service_name'], $itemData['price'], $itemData['quantity'])) {
                continue;
            }

            if (!empty($itemData['id'])) {
                // Modifica un item esistente
                $item = $this->entityManager->getRepository(QuotationItem::class)->find($itemData['id']);
                if ($item && $item->getQuotation() === $quotation) {
                    $item->setServiceName(htmlspecialchars($itemData['service_name'], ENT_QUOTES, 'UTF-8'));
                    $item->setDescription(htmlspecialchars($itemData['description'] ?? '', ENT_QUOTES, 'UTF-8'));
                    $item->setPrice((float)$itemData['price']);
                    $item->setQuantity((int)$itemData['quantity']);
                    $existingItemIds[] = $item->getId();
                    $this->entityManager->persist($item);
                }
            } else {
                // Aggiunge un nuovo item
                $newItem = new QuotationItem();
                $newItem->setServiceName(htmlspecialchars($itemData['service_name'], ENT_QUOTES, 'UTF-8'));
                $newItem->setDescription(htmlspecialchars($itemData['description'] ?? '', ENT_QUOTES, 'UTF-8'));
                $newItem->setPrice((float)$itemData['price']);
                $newItem->setQuantity((int)$itemData['quantity']);
                $newItem->setQuotation($quotation);
                $this->entityManager->persist($newItem);
            }
        }

        // Rimuove gli items eliminati dall'utente
        foreach ($existingItems as $existingItem) {
            if (!in_array($existingItem->getId(), $existingItemIds)) {
                $this->entityManager->remove($existingItem);
            }
        }

        // Salva le modifiche nel database
        $this->entityManager->flush();

        header("Location: /leads/quotations/$leadId");
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
