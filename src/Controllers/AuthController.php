<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class AuthController
{
    private $entityManager;

    // Iniettiamo l'EntityManager
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function login(array $postData)
    {
        $email = $postData['email'];
        $password = $postData['password'];

        // Recupera l'utente dal database usando Doctrine
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        if ($user && password_verify($password, $user->getPassword())) {
            echo "Login successful!";
            // Aggiungi logica per avviare una sessione, reindirizzare, ecc.
        } else {
            echo "Invalid credentials!";
        }
    }

    public function logout()
    {
        // Logica per il logout, come terminare la sessione
        echo "Logged out!";
    }
}
