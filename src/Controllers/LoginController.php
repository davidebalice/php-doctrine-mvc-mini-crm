<?php

namespace App\Controllers;

class LoginController
{
    // Visualizza il form di login
    public function loginForm()
    {
        include __DIR__ . '/../Views/login.php';  // Carica la vista di login
    }

    // Gestisce il login dopo che l'utente ha inviato i dati
    public function loginPost()
    {
        // Recupera i dati del form
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Fai qui la logica di autenticazione (verifica con il database, ecc.)
        if ($username === 'admin' && $password === 'password123') {
            echo "Login successful!";  // Simula il login
        } else {
            echo "Invalid credentials.";
        }
    }
}
