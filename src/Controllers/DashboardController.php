<?php

namespace App\Controllers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Lead;
use App\Entity\Task;
use App\Entity\History;
use App\Entity\Document;
use Doctrine\ORM\Tools\Pagination\Paginator;
class DashboardController extends RenderController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager);
    }
    
    public function dashboard()
    {
        $total_leads=$this->countLeads();
        $leads_active=$this->countActiveLeads();
        $tasks_pending=$this->countPendingTasks();
        $documents=$this->countDocuments();
        $leads=$this->leads();
        $histories=$this->histories();

        $data=[
           'title'=>'Dashboard',
           'description'=>'Welcome to the dashboard',
           'total_leads'=>$total_leads,
           'leads_active'=>$leads_active,
           'tasks_pending'=>$tasks_pending,
           'documents'=>$documents,
           'leads'=>$leads,
           'histories'=>$histories,
       ];
       $this->render('admin/dashboard', $data);
    }

    public function countLeads()
    {
        //utente loggato
        $user = $this->getLoggedInUser();

        return $this->entityManager->getRepository(Lead::class)
            ->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->innerJoin('l.assigned_user', 'u')
            ->where('u.id = :user_id')
            ->setParameter('user_id', $user->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countActiveLeads()
    {
        //utente loggato
        $user = $this->getLoggedInUser();

        return $this->entityManager->getRepository(Lead::class)
            ->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->innerJoin('l.assigned_user', 'u')
            ->where('u.id = :user_id')
            ->setParameter('user_id', $user->getId())
            ->andWhere('l.active = :active')
            ->setParameter('active', 1)
            ->getQuery()->getSingleScalarResult();
    }

    public function countPendingTasks()
    {
        // Utente loggato
        $user = $this->getLoggedInUser();
    
        return $this->entityManager->getRepository(Task::class)
            ->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->innerJoin('t.lead', 'l')  // Join tra Task e Lead
            ->innerJoin('l.assigned_user', 'u')  // Join tra Lead e User
            ->where('u.id = :user_id')  // Filtro sull'utente
            ->setParameter('user_id', $user->getId())
            ->andWhere('t.status = :status')  // Filtro sullo stato del task
            ->setParameter('status', 'Pending')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    public function countDocuments()
    {
        // Utente loggato
        $user = $this->getLoggedInUser();
    
        return $this->entityManager->getRepository(Document::class)
            ->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->innerJoin('d.lead', 'l')  // Join tra Document e Lead
            ->innerJoin('l.assigned_user', 'u')  // Join tra Lead e User
            ->where('u.id = :user_id')  // Filtro sull'utente
            ->setParameter('user_id', $user->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function leads()
    {
        $page = isset($_GET['page']) ? filter_var($_GET['page'], FILTER_VALIDATE_INT) : 1;
        $page = $page && $page > 0 ? $page : 1; // Controllo sulla variabile get page
        $limit = 12;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        //utente loggato
        $user = $this->getLoggedInUser();

        // Impostiamo un limite alla lunghezza della ricerca per evitare query pesanti
        if (strlen($search) > 255) {
            $search = substr($search, 0, 255);
        }

         // Sanifica il valore per prevenire attacchi XSS
        $search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8');

        $queryBuilder = $this->entityManager->getRepository(Lead::class)->createQueryBuilder('s');

        $queryBuilder
        ->innerJoin('s.assigned_user', 'u')
        ->where('u.id = :user_id')
        ->setParameter('user_id', $user->getId());
    
        if (!empty($search)) {
            $queryBuilder->andWhere('s.name LIKE :search')
                        ->setParameter('search', "%$search%");
        }

        $query = $queryBuilder
            ->orderBy('s.created_at', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();


        $paginator = new Paginator($query);

        return $paginator;
    }

    public function histories()
    {
        //utente loggato
        $user = $this->getLoggedInUser();
        
        $page = isset($_GET['page']) ? filter_var($_GET['page'], FILTER_VALIDATE_INT) : 1;
        $page = $page && $page > 0 ? $page : 1; // Controllo sulla variabile get page
        $limit = 12;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Impostiamo un limite alla lunghezza della ricerca per evitare query pesanti
        if (strlen($search) > 255) {
            $search = substr($search, 0, 255);
        }

        $queryBuilder =  $this->entityManager->getRepository(History::class)
              ->createQueryBuilder('h')
              ->innerJoin('h.lead', 'l')
              ->innerJoin('l.assigned_user', 'u')
              ->where('u.id = :user_id')
              ->setParameter('user_id', $user->getId());

        $query = $queryBuilder
            ->orderBy('h.created_at', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);

        return $paginator;
    }
}
