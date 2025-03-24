<?php

namespace App\Controllers;

use App\Entity\Lead;
use App\Entity\Source;
use App\Entity\Status;
use App\Entity\History;
use App\Entity\Quotation;
use App\Entity\Task;
use App\Entity\Call;
use App\Entity\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;
class CallsController extends RenderController
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

    // Ottieni tutti le chiamate
    public function calls($id)
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
        $queryBuilder = $this->entityManager->getRepository(Call::class)->createQueryBuilder('c')
            ->where('c.lead = :lead')
            ->setParameter('lead', $lead);

        /*
        if (!empty($search)) {
            $queryBuilder->andWhere('c.name LIKE :search')
                ->setParameter('search', "%$search%");
        }
        */

        $query = $queryBuilder
            ->orderBy('c.call_time', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);
        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / $limit);

        $data = [
            'title' => 'Calls',
            'description' => 'View calls of lead',
            'lead' => $lead,
            'calls' => $paginator,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ];

        $this->render('/leads/calls', $data);
    }

    public function detail($id) {
        $call = $this->entityManager->getRepository(Call::class)->find($id);

        $data = [
            'call_time' => $call->getCallTime(),
            'status' => $call->getStatus(),
            'notes' => $call->getNotes(),
        ];

        return $this->jsonResponse($data);
    }

    public function store(Request $request) {
        $lead_id = trim($request->request->get('lead_id', ''));

        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/leads/calls/$lead_id';</script>";
            exit();
        }

        // Recupero e sanificazione dei dati inviati dal form
        $call_time = trim($request->request->get('call_time', ''));
        $status = trim($request->request->get('status', ''));
        $notes = trim($request->request->get('notes', ''));
        $lead_id = trim($request->request->get('lead_id', ''));


        $lead = $this->entityManager->getRepository(Lead::class)->find($lead_id);
        if (!$lead) {
            $_SESSION['error'] = "Lead not found";
            header('Location: /leads');
            exit();
        }
        
        // Sanificazione dei dati
        $call_time = htmlspecialchars($call_time, ENT_QUOTES, 'UTF-8');
        $status = htmlspecialchars($status, ENT_QUOTES, 'UTF-8');
        $notes = htmlspecialchars($notes, ENT_QUOTES, 'UTF-8');

        // Verifica se i campi obbligatori sono vuoti
        if (empty($call_time) || empty($notes) || empty($status)) {
            $_SESSION['error'] = "All fields are mandatory";
            header('Location: /leads');
            exit();
        }

        // Crea un nuovo Lead e imposta i dati
        $call = new Call();
        $call_time = new \DateTime($call_time);
        $call->setCallTime($call_time);
        $call->setStatus($status);
        $call->setNotes($notes);
        $call->setLead($lead);

        $this->entityManager->persist($call);
        $this->entityManager->flush();

        header('Location: /leads/calls/'.$lead_id);
        exit();
    }

    public function edit($id) {
        $call = $this->entityManager->getRepository(Call::class)->find($id);

        $data = [
            'call_time' => $call->getCallTime(),
            'status' => $call->getStatus(),
            'notes' => $call->getNotes(),
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

        $id = $request->request->get('call_id');
        $id = (int) $id; // Conversione sicura a intero

        $call = $this->entityManager->getRepository(Call::class)->find($id);

        if (!$call) {
            die('call non found');
        }

        // Recupero e sanificazione dei dati inviati dal form
        $call_time = trim($request->request->get('call_time', ''));
        $status = trim($request->request->get('status', ''));
        $notes = trim($request->request->get('notes', ''));
        $lead_id = trim($request->request->get('lead_id', ''));
  
        
        // Sanificazione dei dati
        $call_time = htmlspecialchars($call_time, ENT_QUOTES, 'UTF-8');
        $status = htmlspecialchars($status, ENT_QUOTES, 'UTF-8');
        $notes = htmlspecialchars($notes, ENT_QUOTES, 'UTF-8');


        // Verifica se i campi obbligatori sono vuoti
        if (empty($call_time) || empty($status) || empty($notes) ) {
            $_SESSION['error'] = "All fields are mandatory";
            header('Location: /leads/calls/'.$lead_id);
            exit();
        }

        $call_time = new \DateTime($call_time);
        $call->setCallTime($call_time);
        $call->setStatus($status);
        $call->setNotes($notes);
      

        $this->entityManager->flush();

        header('Location: /leads/calls/'.$lead_id);
        exit();
    }

    public function delete($lead_id,$id)
    {
        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/leads/calls/$lead_id';</script>";
            exit();
        }

        $call = $this->entityManager->getRepository(Call::class)->find($id);
    
        if ($call) {
            $this->entityManager->remove($call);
            $this->entityManager->flush();
        }
    
        header('Location: /leads/calls/'.$lead_id);
        exit();
    }
}
