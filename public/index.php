<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Routes/routes.php';
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use App\Middlewares\AuthMiddleware;
use Symfony\Component\HttpFoundation\Request;

// Ottieni il metodo HTTP e l'URI della richiesta
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');

// Configura il dispatcher FastRoute
$dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
    defineRoutes($r);
});

// Carica l'EntityManager
$entityManager = require __DIR__ . '/../config/Bootstrap.php';

// Esegui il match della richiesta
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo "404 - Route not found";
        break;
    
    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo "405 - Method not allowed";
        break;
    
    case Dispatcher::FOUND:
        list(, $handler, $vars) = $routeInfo;

        // Mappatura rotte a controller e metodi
        $routesMap = [
            'home' => ['App\\Controllers\\HomeController', 'index'],
            'dashboard' => ['App\\Controllers\\DashboardController', 'dashboard', true],
            'login' => ['App\\Controllers\\LoginController', 'loginForm'],
            'loginPost' => ['App\\Controllers\\LoginController', 'loginPost'],
            'logout' => ['App\\Controllers\\LoginController', 'logout'],
            'registerForm' => ['App\\Controllers\\LoginController', 'registerForm'],
            'registerPost' => ['App\\Controllers\\LoginController', 'registerPost'],
            'leads' => ['App\\Controllers\\LeadsController', 'leads', true],
            
            //sources
            'sources' => ['App\\Controllers\\SourcesController', 'sources', true],
            'sources_create' => ['App\\Controllers\\SourcesController', 'create', true],
            'sources_store' => ['App\\Controllers\\SourcesController', 'store', true],
            'sources_edit' => ['App\\Controllers\\SourcesController', 'edit', true],
            'sources_update' => ['App\\Controllers\\SourcesController', 'update', true],
            'sources_delete' => ['App\\Controllers\\SourcesController', 'delete', true],
            
            //statuses
            'statuses' => ['App\\Controllers\\StatusesController', 'statuses', true],
            'statuses_create' => ['App\\Controllers\\StatusesController', 'create', true],
            'statuses_store' => ['App\\Controllers\\StatusesController', 'store', true],
            'statuses_edit' => ['App\\Controllers\\StatusesController', 'edit', true],
            'statuses_update' => ['App\\Controllers\\StatusesController', 'update', true],
            'statuses_delete' => ['App\\Controllers\\StatusesController', 'delete', true],
        ];
        
        if (isset($routesMap[$handler])) {
            list($controller, $method, $protected) = array_pad($routesMap[$handler], 3, false);
            
            if ($protected) {
                $authMiddleware = new AuthMiddleware();
                if (!$authMiddleware->checkAuth()) {
                    header('Location: /login');
                    exit();
                }
            }
        
            $request = Request::createFromGlobals(); // Crea l'oggetto Request
        
            // Controlla se il metodo del controller richiede l'oggetto Request
            $controllerInstance = new $controller($entityManager);
            $methodParams = (new ReflectionMethod($controllerInstance, $method))->getParameters();
        
            if (!empty($methodParams) && $methodParams[0]->getType() && $methodParams[0]->getType()->getName() === Request::class) {
                $controllerInstance->$method($request, ...array_values($vars));
            } else {
                $controllerInstance->$method(...array_values($vars));
            }
        } else {
            http_response_code(500);
            echo "500 - Handler not found";
        }
        
        break;
}