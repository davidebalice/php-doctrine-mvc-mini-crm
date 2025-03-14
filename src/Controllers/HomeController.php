<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends RenderController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }
    
    public function index()
    {
       $data=[
           'title'=>'Homepage',
           'description'=>'Homepage',
       ];
       $this->render('index', $data);
    }
}
