<?php

namespace App\Controllers;

class DashboardController
{
    public function dashboard()
    {
       include __DIR__ . '/../Views/admin/dashboard.php';
    }
}
