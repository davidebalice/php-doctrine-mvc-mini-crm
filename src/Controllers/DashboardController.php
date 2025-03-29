<?php

namespace App\Controllers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Lead;

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

        $data=[
           'title'=>'Dashboard',
           'description'=>'Welcome to the dashboard',
           'leads'=>$leads,
           'leads_active'=>$leads_active,
       ];
       $this->render('admin/dashboard', $data);
    }

    public function countLeads()
    {
        $queryBuilder = $this->entityManager->getRepository(Lead::class)->createQueryBuilder('l');
        $queryBuilder->select('COUNT(l.id)');
        
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function countActiveLeads()
    {
        return $this->entityManager->getRepository(Lead::class)
            ->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->where('l.active = :active')
            ->setParameter('active', 1)
            ->getQuery()->getSingleScalarResult();
    }
}
