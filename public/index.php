<?php
/*
ini_set('display_errors', 1);
error_reporting(E_ALL);
*/
require_once __DIR__ . '/../vendor/autoload.php';  // Carica Composer
require_once __DIR__ . '/../src/Routes/routes.php';  // Carica le rotte

use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\DashboardController;
use App\Middlewares\AuthMiddleware;

// Ottieni il metodo HTTP e l'URI della richiesta
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Rimuovi la query string dall'URL
$uri = strtok($uri, '?');

// Configura il dispatcher FastRoute
$dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
    defineRoutes($r);  // Definisci le rotte
});

// Carica la configurazione di Doctrine e ottieni l'EntityManager
$entityManager = require __DIR__ . '/../config/Bootstrap.php';

// Esegui il match tra la richiesta e la rotta
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

// Gestisci la risposta
switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        echo "404 - Route not found";
        break;
    case Dispatcher::METHOD_NOT_ALLOWED:
        echo "405 - Method not allowed";
        break;
    case Dispatcher::FOUND:
        // Esegui il controller corrispondente alla rotta
        $handler = $routeInfo[1];

        // Esegui il controller appropriato
        if ($handler === 'home') {
            (new HomeController())->index();
        }
        elseif ($handler === 'dashboard') {
            // Applica il middleware di autenticazione (verifica del token)
            $authMiddleware = new AuthMiddleware();
            // Se il token è valido, esegui la logica del controller per il dashboard
            if ($authMiddleware->checkAuth()) {
                // Se il token è valido, esegui il controller per il dashboard
                $dashboardController = new DashboardController();
                $dashboardController->dashboard(); // Questo carica la vista del dashboard
            } else {
                //echo "Accesso negato. Devi essere loggato per accedere a questa pagina.";
                header('Location: /login');  // Se il token non è valido, reindirizza alla pagina di login
            }
        }
        elseif ($handler === 'login') {
            (new LoginController($entityManager))->loginForm();
        }
        elseif ($handler === 'loginPost') {
            (new LoginController($entityManager))->loginPost();
        }
        elseif ($handler === 'logout') {
            (new LoginController($entityManager))->logout();
        }
        elseif ($handler === 'registerForm') {
            (new LoginController($entityManager))->registerForm();
        }
        elseif ($handler === 'registerPost') {
            (new LoginController($entityManager))->registerPost();
        }
        elseif ($handler === 'about') {
            echo "About us page";
        }
        break;
}
