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
use App\Controllers\LeadsController;
use App\Controllers\SourcesController;
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

// Funzione per gestire le rotte protette automaticamente
function handleProtectedRoute($controllerClass, $method, $entityManager) {
    $authMiddleware = new AuthMiddleware();
    if ($authMiddleware->checkAuth()) {
        (new $controllerClass($entityManager))->$method();
    } else {
        header('Location: /login');
        exit();
    }
}

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
            (new HomeController($entityManager))->index();
        }
        elseif ($handler === 'dashboard') {
            handleProtectedRoute(DashboardController::class, 'dashboard', $entityManager);
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
        
        //Leads
        elseif ($handler === 'leads') {
            handleProtectedRoute(LeadsController::class, 'leads', $entityManager);
        }

        //Sources
        elseif ($handler === 'sources') {
            handleProtectedRoute(SourcesController::class, 'sources', $entityManager);
        }

        elseif ($handler === 'about') {
            echo "About us page";
        }
        break;
}
