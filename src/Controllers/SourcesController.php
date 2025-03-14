<?php

namespace App\Controllers;

use App\Entity\Source;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class SourcesController extends RenderController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager);
    }

    // Ottieni tutti i sources
    public function sources()
    {
        $sources = $this->entityManager->getRepository(Source::class)->findAll();

        $data = [
            'title' => 'Sources',
            'description' => 'Lista di tutte le fonti',
            'sources' => $sources
        ];
        
        $this->render('/sources/sources', $data);
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
