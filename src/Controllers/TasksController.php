<?php

namespace App\Controllers;

use App\Entity\Lead;
use App\Entity\History;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Services\HistoryService;

class TasksController extends RenderController
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

    // Ottieni tutti i tasks
    public function tasks($id)
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
        $queryBuilder = $this->entityManager->getRepository(Task::class)->createQueryBuilder('c')
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
            'title' => 'Tasks',
            'description' => 'View tasks of lead',
            'lead' => $lead,
            'tasks' => $paginator,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ];

        $this->render('/leads/tasks', $data);
    }

    public function detail($id) {
        $task = $this->entityManager->getRepository(Task::class)->find($id);

        $data = [
            'due_date' => $task->getDueDate(),
            'status' => $task->getStatus(),
            'description' => $task->getDescription(),
        ];

        return $this->jsonResponse($data);
    }

    public function store(Request $request) {
        $lead_id = trim($request->request->get('lead_id', ''));

        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/leads/tasks/$lead_id';</script>";
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
        $task = new Task();
        $due_date = new \DateTime($due_date);
        $task->setDueDate($due_date);
        $task->setStatus($status);
        $task->setDescription($description);
        $task->setLead($lead);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        //crea la voce in history
        $length=50;
        $short_description =  strlen($description) > $length ? substr($description, 0, $length) . '...' : $description;
        $this->historyService->logAction('Create new task: '.$short_description, 'Task');
        //

        header('Location: /leads/tasks/'.$lead_id);
        exit();
    }

    public function edit($id) {
        $task = $this->entityManager->getRepository(Task::class)->find($id);

        $data = [
            'due_date' => $task->getDueDate(),
            'status' => $task->getStatus(),
            'description' => $task->getDescription(),
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

        $id = $request->request->get('task_id');
        $id = (int) $id; // Conversione sicura a intero

        $task = $this->entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            die('task non found');
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
            header('Location: /leads/tasks/'.$lead_id);
            exit();
        }

        $due_date = new \DateTime($due_date);
        $task->setDueDate($due_date);
        $task->setStatus($status);
        $task->setDescription($description);
      

        $this->entityManager->flush();

        //crea la voce in history
        $length=50;
        $short_description =  strlen($description) > $length ? substr($description, 0, $length) . '...' : $description;
        $this->historyService->logAction('Update task: '.$short_description, 'Task');
        //

        header('Location: /leads/tasks/'.$lead_id);
        exit();
    }

    public function delete($lead_id,$id)
    {
        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed');
            window.location.href='/leads/tasks/$lead_id';</script>";
            exit();
        }

        $task = $this->entityManager->getRepository(Task::class)->find($id);
    
        if ($task) {
            $this->entityManager->remove($task);
            $this->entityManager->flush();
        }

        //crea la voce in history
        $this->historyService->logAction('Delete task', 'Task');
        //
    
        header('Location: /leads/tasks/'.$lead_id);
        exit();
    }
}
