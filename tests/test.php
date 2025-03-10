<?php
require_once __DIR__ . '/../config/bootstrap.php';

use App\Repository\UserRepository;

$repository = new UserRepository($entityManager);

// Crea un utente
$user = $repository->createUser('Mario Rossi', 'mario@example.com', 'password123');

echo "Utente creato con ID: " . $user->getId() . "\n";

// Trova un utente per email
$foundUser = $repository->findUserByEmail('mario@example.com');

if ($foundUser) {
    echo "Utente trovato: " . $foundUser->getName() . " (" . $foundUser->getEmail() . ")\n";
} else {
    echo "Utente non trovato\n";
}
