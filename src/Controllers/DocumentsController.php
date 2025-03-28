<?php

namespace App\Controllers;

use App\Entity\Lead;
use App\Entity\History;
use App\Entity\Document;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
class DocumentsController extends RenderController
{
    private $entityManager;
    private $uploadDir = __DIR__ . '/../../public/uploads/documents/';
    
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

    // Ottieni tutti i documents
    public function documents($id)
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
        $queryBuilder = $this->entityManager->getRepository(Document::class)->createQueryBuilder('d')
            ->where('d.lead = :lead')
            ->setParameter('lead', $lead);

        /*
        if (!empty($search)) {
            $queryBuilder->andWhere('d.name LIKE :search')
                ->setParameter('search', "%$search%");
        }
        */

        $query = $queryBuilder
            ->orderBy('d.title', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);
        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / $limit);

        $data = [
            'title' => 'Documents',
            'description' => 'View documents of lead',
            'lead' => $lead,
            'documents' => $paginator,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ];

        $this->render('/leads/documents', $data);
    }

    public function detail($id) {
        $document = $this->entityManager->getRepository(Document::class)->find($id);

        $data = [
            'title' => $document->getTitle(),
            'filename' => $document->getFilename(),
        ];

        return $this->jsonResponse($data);
    }

    public function store(Request $request)
    {
        $lead_id = trim($request->request->get('lead_id', ''));
        $lead = $this->entityManager->getRepository(Lead::class)->find($lead_id);
        
        if (!$lead) {
            return $this->jsonResponse(['error' => 'Lead not found'], 400);
        }

        $file = $request->files->get('file');
        
        if (!$file instanceof UploadedFile) {
            return $this->jsonResponse(['error' => 'No valid file uploaded'], 400);
        }

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->guessExtension();
        $newFilename = $lead_id . '_' . $originalFilename . '.' . $extension;

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
        
        $file->move($this->uploadDir, $newFilename);

        $document = new Document();
        $document->setTitle($originalFilename);
        $document->setFilename($newFilename);
        $document->setLead($lead);

        $this->entityManager->persist($document);
        $this->entityManager->flush();

        header('Location: /leads/documents/'.$lead_id);
        exit();
    }

    public function edit($id) {
        $document = $this->entityManager->getRepository(Document::class)->find($id);

        $data = [
            'title' => $document->getTitle(),
            'filename' => $document->getFilename(),
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

      

        $id = $request->request->get('document_id');
        $id = (int) $id; // Conversione sicura a intero

      

        $document = $this->entityManager->getRepository(Document::class)->find($id);

        if (!$document) {
            die('document non found');
        }

        // Recupero e sanificazione dei dati inviati dal form
        $title = trim($request->request->get('title', ''));
        //$filename = trim($request->request->get('filename', ''));
        $lead_id = trim($request->request->get('lead_id', ''));

       
        
        // Sanificazione dei dati
        $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        //$filename = htmlspecialchars($filename, ENT_QUOTES, 'UTF-8');


        // Verifica se i campi obbligatori sono vuoti
        if (empty($title) ) {
            $_SESSION['error'] = "All fields are mandatory";
            header('Location: /leads/documents/'.$lead_id);
            exit();
        }

        $document->setTitle($title);
       // $document->setFilename($filename);
      
    

        $this->entityManager->flush();
        

        header('Location: /leads/documents/'.$lead_id);
        exit();
    }

    public function delete($lead_id, $id)
    {
        if (DEMO_MODE) {
            echo "<script>alert('Demo mode: crud operations not allowed'); 
            window.location.href='/leads/documents/$lead_id';</script>";
            exit();
        }
    
        // Trova il documento dal database
        $document = $this->entityManager->getRepository(Document::class)->find($id);
    
        if ($document) {
            // Ottieni il nome del file da eliminare
            $filename = $document->getFilename();
            $filePath = $this->uploadDir . $filename;
    
            // Verifica se il file esiste e cancellalo
            if (file_exists($filePath)) {
                unlink($filePath); // Cancella il file fisico
            }
    
            // Rimuovi il documento dal database
            $this->entityManager->remove($document);
            $this->entityManager->flush();
        }
    
        // Redirect dopo l'operazione di cancellazione
        header('Location: /leads/documents/'.$lead_id);
        exit();
    }
    
}
