<?php

namespace App\Controllers;

use App\Entity\User;
use Firebase\JWT\JWT;
use Doctrine\ORM\EntityManagerInterface;

class LoginController extends RenderController
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
        $data=[
            'title'=>'Login',
            'description'=>'User login',
        ];
        $this->render('login', $data);
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
        header('Location: /login');
    }

    // Gestisce la registrazione di un nuovo utente
    public function registerForm()
    {
        // Carica il modulo di registrazione
        $data=[
            'title'=>'Register',
            'description'=>'User registration',
        ];
        $this->render('register', $data);
    }

    public function registerPost()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Verifica se le password corrispondono
        if ($password !== $confirmPassword) {
            echo json_encode(['error' => 'Le password non corrispondono.']);
            exit();
        }

        // Verifica se l'utente esiste già
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($existingUser) {
            echo json_encode(['error' => 'Un utente con questa email esiste già.']);
            exit();
        }

        // Crea un nuovo utente
        $user = new User();
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));  // Cripta la password

        // Salva l'utente nel database
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        echo json_encode(['success' => 'Registrazione avvenuta con successo!']);
    }
}
