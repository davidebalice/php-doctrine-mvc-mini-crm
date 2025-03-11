<?php

namespace App\Controllers;

use App\Entity\User;
use Firebase\JWT\JWT;
use Doctrine\ORM\EntityManagerInterface;

class LoginController
{
    private $entityManager;
    private $secretKey = 'F934gG034jkgg431f';  // Chiave segreta per firmare il token

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Carica la vista di login
    public function loginForm()
    {
        include __DIR__ . '/../Views/login.php';
    }

    // Gestisce il login dopo che l'utente ha inviato i dati
    public function loginPost()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Verifica se l'utente esiste nel database
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user && password_verify($password, $user->getPassword())) {
            // Login riuscito, crea il JWT
            $jwt = $this->createJWT($user);

             // Salva il token JWT nel cookie
             setcookie('jwt_token', $jwt, time() + 3600, '/', '', true, true);  // Scadenza 1 ora, cookie sicuro e HttpOnly

             // Redirigi l'utente alla pagina protetta
             header('Location: /dashboard');
             exit();
        } else {
            // Login fallito
            echo json_encode(['error' => 'Invalid credentials.']);
            exit();
        }
    }

    // Crea un token JWT
    private function createJWT(User $user)
    {
        $payload = [
            "iss" => "http://localhost",
            "aud" => "http://localhost",
            "iat" => time(), // Data di emissione
            "exp" => time() + 3600, // Scadenza tra 1 ora
            'user_id' => $user->getId(),
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    public function logout()
    {
        // Rimuove il cookie JWT
        setcookie("jwt_token", "", time() - 3600, "/");

        // Messaggio di logout
        echo json_encode(["message" => "Logout effettuato con successo"]);
    }
}
