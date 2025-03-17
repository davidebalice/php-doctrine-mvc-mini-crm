<?php

require_once '../vendor/autoload.php';

use FastRoute\RouteCollector;

function defineRoutes(RouteCollector $r) {
    // Mappa la rotta alla funzione del controller
    $r->addRoute('GET', '/', 'home');
    $r->addRoute('GET', '/about', 'about');
    $r->addRoute('GET', '/dashboard', 'dashboard');
    
    //login / register routes
    $r->addRoute('GET', '/login', 'login');
    $r->addRoute('POST', '/login', 'loginPost');
    $r->addRoute('GET', '/logout', 'logout');
    $r->addRoute('GET', '/register', 'registerForm');
    $r->addRoute('POST', '/register', 'registerPost');

    //Leads
    $r->addRoute('GET', '/leads', 'leads');

    //Sources
    $r->addRoute('GET', '/sources', 'sources');

    //Statuses
    $r->addRoute('GET', '/statuses', 'statuses');
    $r->addRoute('GET', '/statuses/create', 'statuses_create');
    $r->addRoute('POST', '/statuses/create', 'statuses_store');
    $r->addRoute('GET', '/statuses/edit/{id:\d+}', 'status_edit');
    $r->addRoute('POST', '/statuses/edit/{id:\d+}', 'status_update');
    $r->addRoute('GET', '/statuses/delete/{id:\d+}', 'statuses_delete');

}