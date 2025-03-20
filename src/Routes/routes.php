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
    $r->addRoute('GET', '/leads/create', 'leads_create');
    $r->addRoute('POST', '/leads/store', 'leads_store');
    $r->addRoute('GET', '/leads/detail/{id:\d+}', 'leads_detail');
    $r->addRoute('GET', '/leads/edit/{id:\d+}', 'leads_edit');
    $r->addRoute('POST', '/leads/update', 'leads_update');
    $r->addRoute('GET', '/leads/delete/{id:\d+}', 'leads_delete');

    //Sources
    $r->addRoute('GET', '/sources', 'sources');
    $r->addRoute('GET', '/sources/create', 'sources_create');
    $r->addRoute('POST', '/sources/store', 'sources_store');
    $r->addRoute('GET', '/sources/edit/{id:\d+}', 'sources_edit');
    $r->addRoute('POST', '/sources/update', 'sources_update');
    $r->addRoute('GET', '/sources/delete/{id:\d+}', 'sources_delete');

    //Statuses
    $r->addRoute('GET', '/statuses', 'statuses');
    $r->addRoute('GET', '/statuses/create', 'statuses_create');
    $r->addRoute('POST', '/statuses/store', 'statuses_store');
    $r->addRoute('GET', '/statuses/edit/{id:\d+}', 'statuses_edit');
    $r->addRoute('POST', '/statuses/update', 'statuses_update');
    $r->addRoute('GET', '/statuses/delete/{id:\d+}', 'statuses_delete');

}