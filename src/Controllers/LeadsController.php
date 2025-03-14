<?php

namespace App\Controllers;

use App\Entity\Lead;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

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
        $leads = $this->entityManager->getRepository(Lead::class)->findAll();

        $data = [
            'title' => 'Leads',
            'description' => 'Lista di tutti i leads',
            'leads' => $leads
        ];
        
        $this->render('/leads/leads', $data);
    }

    /*
    // Crea un nuovo lead
    public function store(Request $request)
    {
        $lead = new Lead();
        $lead->setName($request->request->get('name'));
        $lead->setEmail($request->request->get('email'));
        $lead->setPhone($request->request->get('phone'));

        $this->entityManager->persist($lead);
        $this->entityManager->flush();

        header('Location: /leads'); // Reindirizza alla lista leads
        exit();
    }

    // Modifica un lead esistente
    public function update(Request $request, $id)
    {
        $lead = $this->entityManager->getRepository(Lead::class)->find($id);

        if (!$lead) {
            die('Lead non trovato');
        }

        $lead->setName($request->request->get('name'));
        $lead->setEmail($request->request->get('email'));
        $lead->setPhone($request->request->get('phone'));

        $this->entityManager->flush();

        header('Location: /leads');
        exit();
    }

    // Cancella un lead
    public function delete($id)
    {
        $lead = $this->entityManager->getRepository(Lead::class)->find($id);

        if (!$lead) {
            die('Lead non trovato');
        }

        $this->entityManager->remove($lead);
        $this->entityManager->flush();

        header('Location: /leads');
        exit();
    }
        */
}
