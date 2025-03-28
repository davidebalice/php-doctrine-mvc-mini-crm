<?php

namespace App\Controllers;

use App\Entity\Lead;
use App\Entity\Source;
use App\Entity\Status;
use App\Entity\History;
use App\Entity\Quotation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;
class LeadsController extends RenderController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager);
    }

    // Ottieni tutti i leads
    public function leads()
    {
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

        $queryBuilder = $this->entityManager->getRepository(Lead::class)->createQueryBuilder('s');

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
            'title' => 'Leads',
            'description' => 'View of all leads',
            'leads' => $paginator,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ];

        $this->render('/leads/leads', $data);
    }

    public function detail($id) {
        $lead = $this->entityManager->getRepository(Lead::class)->find($id);

        $data = [
            'title' => 'Lead details',
            'description' => 'Lead details',
            'lead' => $lead,
        ];

        $this->render('/leads/detail', $data);
    }

    public function create() {
        $sources = $this->entityManager->getRepository(Source::class)
            ->createQueryBuilder('s')
            ->orderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult();

        $statuses = $this->entityManager->getRepository(Status::class)
            ->createQueryBuilder('s')
            ->orderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult();
        
        $data = [
            'title' => 'New lead',
            'description' => 'Create new lead',
            'sources' => $sources,
            'statuses' => $statuses,
        ];

        $this->render('/leads/new', $data);
    }

    public function store(Request $request) {
        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/leads';</script>";
            exit();
        }

        //utente loggato
        $user = $this->getLoggedInUser();

        // Recupero e sanificazione dei dati inviati dal form
        $name = trim($request->request->get('name', ''));
        $surname = trim($request->request->get('surname', ''));
        $email = trim($request->request->get('email', ''));
        $phone = trim($request->request->get('phone', ''));
        $city = trim($request->request->get('city', ''));
        $address = trim($request->request->get('address', ''));
        $zip = trim($request->request->get('zip', ''));
        $country = trim($request->request->get('country', ''));
        $notes = trim($request->request->get('notes', ''));
        
         // Recupera il valore selezionato per Source e Status
        $sourceId = $request->request->get('source', '');
        $statusId = $request->request->get('status', '');

        // Recupera l'entità Source corrispondente
        $source = $this->entityManager->getRepository(Source::class)->find($sourceId);
        if (!$source) {
            $_SESSION['error'] = "Invalid source selected";
            header('Location: /sources/create');
            exit();
        }

        // Recupera l'entità Status corrispondente
        $status = $this->entityManager->getRepository(Status::class)->find($statusId);
        if (!$status) {
            $_SESSION['error'] = "Invalid status selected";
            header('Location: /sources/create');
            exit();
        }

        // Sanificazione dei dati
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $surname = htmlspecialchars($surname, ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $phone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
        $city = htmlspecialchars($city, ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($address, ENT_QUOTES, 'UTF-8');
        $zip = htmlspecialchars($zip, ENT_QUOTES, 'UTF-8');
        $country = htmlspecialchars($country, ENT_QUOTES, 'UTF-8');
        $notes = htmlspecialchars($notes, ENT_QUOTES, 'UTF-8');

        // Verifica se i campi obbligatori sono vuoti
        if (empty($name) || empty($email) || empty($phone) || empty($city) || empty($country) || empty($address) || empty($zip) || empty($sourceId) || empty($statusId)) {
            $_SESSION['error'] = "All fields are mandatory";
            header('Location: /leads/create');
            exit();
        }

        // Crea un nuovo Lead e imposta i dati
        $lead = new Lead();
        $lead->setFirstName($name);
        $lead->setLastName($surname);
        $lead->setEmail($email);
        $lead->setPhone($phone);
        $lead->setCity($city);
        $lead->setAddress($address);
        $lead->setZip($zip);
        $lead->setCountry($country);
        $lead->setAssignedUser($user);
        $lead->setNotes($notes);

        // Imposta source e status
        $lead->setSource($source);
        $lead->setStatus($status);

        $currentDate = new \DateTime();
        $lead->setCreatedAt($currentDate);
        $lead->setUpdatedAt($currentDate);

        $this->entityManager->persist($lead);
        $this->entityManager->flush();

        header('Location: /leads');
        exit();
    }

    public function edit($id) {
        $lead = $this->entityManager->getRepository(Lead::class)->find($id);

        $sources = $this->entityManager->getRepository(Source::class)
            ->createQueryBuilder('s')
            ->orderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult();

        $statuses = $this->entityManager->getRepository(Status::class)
            ->createQueryBuilder('s')
            ->orderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult();

        $data = [
            'title' => 'Edit lead',
            'description' => 'Edit lead',
            'lead' => $lead,
            'sources' => $sources,
            'statuses' => $statuses,
        ];

        $this->render('/leads/edit', $data);
    }

    public function update(Request $request)
    {
        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/leads';</script>";
            exit();
        }

        //utente loggato
        $user = $this->getLoggedInUser();

        $id = $request->request->get('id');
        $id = (int) $id; // Conversione sicura a intero

        $lead = $this->entityManager->getRepository(Lead::class)->find($id);

        if (!$lead) {
            die('lead non found');
        }

        // Recupero e sanificazione dei dati inviati dal form
        $name = trim($request->request->get('name', ''));
        $surname = trim($request->request->get('surname', ''));
        $email = trim($request->request->get('email', ''));
        $phone = trim($request->request->get('phone', ''));
        $city = trim($request->request->get('city', ''));
        $address = trim($request->request->get('address', ''));
        $zip = trim($request->request->get('zip', ''));
        $country = trim($request->request->get('country', ''));
        $notes = trim($request->request->get('notes', ''));
        
         // Recupera il valore selezionato per Source e Status
        $sourceId = $request->request->get('source', '');
        $statusId = $request->request->get('status', '');

        // Recupera l'entità Source corrispondente
        $source = $this->entityManager->getRepository(Source::class)->find($sourceId);
        if (!$source) {
            $_SESSION['error'] = "Invalid source selected";
            header('Location: /sources/create');
            exit();
        }

        // Recupera l'entità Status corrispondente
        $status = $this->entityManager->getRepository(Status::class)->find($statusId);
        if (!$status) {
            $_SESSION['error'] = "Invalid status selected";
            header('Location: /sources/create');
            exit();
        }

        // Sanificazione dei dati
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $surname = htmlspecialchars($surname, ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $phone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
        $city = htmlspecialchars($city, ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($address, ENT_QUOTES, 'UTF-8');
        $zip = htmlspecialchars($zip, ENT_QUOTES, 'UTF-8');
        $country = htmlspecialchars($country, ENT_QUOTES, 'UTF-8');
        $notes = htmlspecialchars($notes, ENT_QUOTES, 'UTF-8');

        // Verifica se i campi obbligatori sono vuoti
        if (empty($name) || empty($email) || empty($phone) || empty($city) || empty($country) || empty($address) || empty($zip) || empty($sourceId) || empty($statusId)) {
            $_SESSION['error'] = "All fields are mandatory";
            header('Location: /leads/edit');
            exit();
        }

        // Crea un nuovo Lead e imposta i dati
        $lead->setFirstName($name);
        $lead->setLastName($surname);
        $lead->setEmail($email);
        $lead->setPhone($phone);
        $lead->setCity($city);
        $lead->setAddress($address);
        $lead->setZip($zip);
        $lead->setCountry($country);
        $lead->setAssignedUser($user);
        $lead->setNotes($notes);

        // Imposta source e status
        $lead->setSource($source);
        $lead->setStatus($status);

        $currentDate = new \DateTime();
        $lead->setUpdatedAt($currentDate);

        $this->entityManager->flush();

        header('Location: /leads');
        exit();
    }

    public function delete($id)
    {
        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/leads';</script>";
            exit();
        }

        $lead = $this->entityManager->getRepository(Lead::class)->find($id);
    
        if ($lead) {
            $this->entityManager->remove($lead);
            $this->entityManager->flush();
        }
    
        header('Location: /leads');
        exit();
    }

    public function history($id) {
        $lead = $this->entityManager->getRepository(Lead::class)->find($id);

        $histories = $this->entityManager->getRepository(History::class)
            ->createQueryBuilder('h')
            ->where('h.lead = :lead')
            ->setParameter('lead', $lead)
            ->orderBy('h.created_at', 'DESC')
            ->getQuery()
            ->getResult();

        $data = [
            'title' => 'Lead details',
            'description' => 'Lead details',
            'lead' => $lead,
            'histories' => $histories,
        ];

        $this->render('/leads/history', $data);
    }

    public function quotations($id) {
        $lead = $this->entityManager->getRepository(Lead::class)->find($id);

        $quotations = $this->entityManager->getRepository(Quotation::class)
            ->createQueryBuilder('q')
            ->where('q.lead = :lead')
            ->setParameter('lead', $lead)
            ->orderBy('q.created_at', 'DESC')
            ->getQuery()
            ->getResult();

        $data = [
            'title' => 'Lead details',
            'description' => 'Lead details',
            'lead' => $lead,
            'quotations' => $quotations,
        ];

        $this->render('/leads/quotations', $data);
    }

}
