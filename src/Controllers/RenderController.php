<?php
namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


class RenderController
{
    private $secretKey = 'F934gG034jkgg431f';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    // Metodo di rendering che verrà utilizzato dai controller
    protected function render($viewName, $data = [])
    {
        // Estrai i dati per utilizzarli nelle viste
        extract($data);

        // Determina il contenuto da caricare
        $content = __DIR__ . '/../../src/Views/' . $viewName . '.php';

         // Ottieni l'utente loggato
         $user = $this->getLoggedInUser();
        
         // Aggiungi l'utente ai dati
         $data['user'] = $user;

        // Carica il layout
        include __DIR__ . '/../../src/Views/layout.php';
    }

    public function getLoggedInUser()
    {
        if (isset($_COOKIE['jwt_token'])) {
            try {
                $decoded = JWT::decode($_COOKIE['jwt_token'], new Key($this->secretKey, 'HS256'));
                // Ottieni l'utente dal database usando l'id
                return $this->entityManager->getRepository(User::class)->findOneBy(['id' => $decoded->user_id]);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }
}
