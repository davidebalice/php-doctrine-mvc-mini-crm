<?php

namespace App\Controllers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Lead;
use App\Entity\Task;
use App\Entity\Document;

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
        $leads=$this->countLeads();
        $leads_active=$this->countActiveLeads();
        $tasks_pending=$this->countPendingTasks();
        $documents=$this->countDocuments();

        $data=[
           'title'=>'Dashboard',
           'description'=>'Welcome to the dashboard',
           'leads'=>$leads,
           'leads_active'=>$leads_active,
           'tasks_pending'=>$tasks_pending,
           'documents'=>$documents,
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
}
