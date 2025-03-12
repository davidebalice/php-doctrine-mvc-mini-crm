<?php
namespace App\Controllers;

class RenderController
{
    // Metodo di rendering che verrà utilizzato dai controller
    protected function render($viewName, $data = [])
    {
        // Estrai i dati per utilizzarli nelle viste
        extract($data);

        // Determina il contenuto da caricare
        $content = __DIR__ . '/../../src/Views/' . $viewName . '.php'; // Cerca la vista

        // Carica il layout
        include __DIR__ . '/../../src/Views/layout.php';
    }
}
