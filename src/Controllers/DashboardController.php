<?php

namespace App\Controllers;



class DashboardController extends RenderController
{
    
    
    public function dashboard()
    {
       $data=[
           'title'=>'Dashboard',
           'description'=>'Welcome to the dashboard',
       ];
       $this->render('admin/dashboard', $data);
    }
}
