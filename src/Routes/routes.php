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
    $r->addRoute('GET', '/leads/history/{id:\d+}', 'leads_history');

    //Leads quotations
    $r->addRoute('GET', '/leads/quotations/{id:\d+}', 'leads_quotations');
    $r->addRoute('GET', '/leads/quotations/{id:\d+}/create', 'leads_quotations_create');
    $r->addRoute('POST', '/leads/quotations/store', 'leads_quotations_store');
    $r->addRoute('GET', '/leads/quotations/{lead_id:\d+}/detail/{id:\d+}', 'leads_quotations_detail');
    $r->addRoute('GET', '/leads/quotations/{lead_id:\d+}/edit/{id:\d+}', 'leads_quotations_edit');
    $r->addRoute('POST', '/leads/quotations/update', 'leads_quotations_update');
    $r->addRoute('GET', '/leads/quotations/delete/{id:\d+}', 'leads_quotations_delete');

    //Quotations
    $r->addRoute('GET', '/quotations', 'quotations');
    $r->addRoute('GET', '/quotations/create', 'quotations_create');
    $r->addRoute('POST', '/quotations/store', 'quotations_store');
    $r->addRoute('GET', '/quotations/detail/{id:\d+}', 'quotations_detail');
    $r->addRoute('GET', '/quotations/edit/{id:\d+}', 'quotations_edit');
    $r->addRoute('POST', '/quotations/update', 'quotations_update');
    $r->addRoute('GET', '/quotations/delete/{id:\d+}', 'quotations_delete');

    //Calls
    $r->addRoute('GET', '/leads/calls/{id:\d+}', 'leads_calls');
    $r->addRoute('POST', '/leads/calls/store', 'leads_calls_store');
    $r->addRoute('GET', '/leads/calls/edit/{id:\d+}', 'leads_calls_edit');
    $r->addRoute('GET', '/leads/calls/detail/{id:\d+}', 'leads_calls_detail');
    $r->addRoute('POST', '/leads/calls/update', 'leads_calls_update');
    $r->addRoute('GET', '/leads/calls/{lead_id:\d+}/delete/{id:\d+}', 'leads_calls_delete');

    //Tasks
    $r->addRoute('GET', '/leads/tasks/{id:\d+}', 'leads_tasks');
    $r->addRoute('POST', '/leads/tasks/store', 'leads_tasks_store');
    $r->addRoute('GET', '/leads/tasks/edit/{id:\d+}', 'leads_tasks_edit');
    $r->addRoute('GET', '/leads/tasks/detail/{id:\d+}', 'leads_tasks_detail');
    $r->addRoute('POST', '/leads/tasks/update', 'leads_tasks_update');
    $r->addRoute('GET', '/leads/tasks/{lead_id:\d+}/delete/{id:\d+}', 'leads_tasks_delete');

    //Documents
    $r->addRoute('GET', '/leads/documents/{id:\d+}', 'leads_documents');
    $r->addRoute('POST', '/leads/documents/store', 'leads_documents_store');
    $r->addRoute('GET', '/leads/documents/edit/{id:\d+}', 'leads_documents_edit');
    $r->addRoute('GET', '/leads/documents/detail/{id:\d+}', 'leads_documents_detail');
    $r->addRoute('POST', '/leads/documents/update', 'leads_documents_update');
    $r->addRoute('GET', '/leads/documents/{lead_id:\d+}/delete/{id:\d+}', 'leads_documents_delete');

    //Notes
    $r->addRoute('GET', '/leads/notes/{id:\d+}', 'leads_notes');
    $r->addRoute('POST', '/leads/notes/store', 'leads_notes_store');
    $r->addRoute('GET', '/leads/notes/edit/{id:\d+}', 'leads_notes_edit');
    $r->addRoute('GET', '/leads/notes/detail/{id:\d+}', 'leads_notes_detail');
    $r->addRoute('POST', '/leads/notes/update', 'leads_notes_update');
    $r->addRoute('GET', '/leads/notes/{lead_id:\d+}/delete/{id:\d+}', 'leads_notes_delete');

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