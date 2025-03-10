<?php

require_once __DIR__ . '/../vendor/autoload.php';  // Carica Composer
require_once __DIR__ . '/../src/Routes/routes.php';  // Carica le rotte

use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use App\Controllers\HomeController;
use App\Controllers\LoginController;

// Ottieni il metodo HTTP e l'URI della richiesta
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Rimuovi la query string dall'URL
$uri = strtok($uri, '?');

// Configura il dispatcher FastRoute
$dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
    defineRoutes($r);  // Definisci le rotte
});

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
        } elseif ($handler === 'login') {
            (new LoginController())->loginForm();
        } elseif ($handler === 'loginPost') {
            (new LoginController())->loginPost();
        } elseif ($handler === 'about') {
            echo "About us page";
        }
        break;
}
