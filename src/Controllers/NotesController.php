<?php

namespace App\Controllers;

use App\Entity\Lead;
use App\Entity\History;
use App\Entity\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Services\HistoryService;
class NotesController extends RenderController
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

    // Ottieni tutti i notes
    public function notes($id)
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
        $queryBuilder = $this->entityManager->getRepository(Note::class)->createQueryBuilder('c')
            ->where('c.lead = :lead')
            ->setParameter('lead', $lead);

        /*
        if (!empty($search)) {
            $queryBuilder->andWhere('c.name LIKE :search')
                ->setParameter('search', "%$search%");
        }
        */

        $query = $queryBuilder
            ->orderBy('c.created_at', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);
        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / $limit);

        $data = [
            'title' => 'Notes',
            'description' => 'View notes of lead',
            'lead' => $lead,
            'notes' => $paginator,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ];

        $this->render('/leads/notes', $data);
    }

    public function detail($id) {
        $note = $this->entityManager->getRepository(Note::class)->find($id);

        $data = [
            'created_at' => $note->getCreatedAt(),
            'content' => $note->getContent(),
        ];

        return $this->jsonResponse($data);
    }

    public function store(Request $request) {
        $lead_id = trim($request->request->get('lead_id', ''));

        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/leads/notes/$lead_id';</script>";
            exit();
        }

        // Recupero e sanificazione dei dati inviati dal form
        $content = trim($request->request->get('content', ''));
        $lead_id = trim($request->request->get('lead_id', ''));


        $lead = $this->entityManager->getRepository(Lead::class)->find($lead_id);
        if (!$lead) {
            $_SESSION['error'] = "Lead not found";
            header('Location: /leads');
            exit();
        }
        
        // Sanificazione dei dati
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

        // Verifica se i campi obbligatori sono vuoti
        if (empty($content)) {
            $_SESSION['error'] = "All fields are mandatory";
            header('Location: /leads/notes/'.$lead_id);
            exit();
        }

        // Crea un nuovo Lead e imposta i dati
        $note = new Note();
        $created_at = new \DateTime();
        $note->setCreatedAt($created_at);
        $note->setContent($content);
        $note->setLead($lead);

        $this->entityManager->persist($note);
        $this->entityManager->flush();

        header('Location: /leads/notes/'.$lead_id);
        exit();
    }

    public function edit($id) {
        $note = $this->entityManager->getRepository(Note::class)->find($id);

        $data = [
            'content' => $note->getContent(),
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

        $id = $request->request->get('note_id');
        $id = (int) $id; // Conversione sicura a intero

        $note = $this->entityManager->getRepository(Note::class)->find($id);

        if (!$note) {
            die('note non found');
        }

        // Recupero e sanificazione dei dati inviati dal form
        $content = trim($request->request->get('content', ''));
        $lead_id = trim($request->request->get('lead_id', ''));
  
        
        // Sanificazione dei dati
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');


        // Verifica se i campi obbligatori sono vuoti
        if (empty($content) ) {
            $_SESSION['error'] = "All fields are mandatory";
            header('Location: /leads/notes/'.$lead_id);
            exit();
        }

        $note->setContent($content);
      
        $this->entityManager->flush();

        header('Location: /leads/notes/'.$lead_id);
        exit();
    }

    public function delete($lead_id,$id)
    {
        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/leads/notes/$lead_id';</script>";
            exit();
        }

        $note = $this->entityManager->getRepository(Note::class)->find($id);
    
        if ($note) {
            $this->entityManager->remove($note);
            $this->entityManager->flush();
        }
    
        header('Location: /leads/notes/'.$lead_id);
        exit();
    }
}
