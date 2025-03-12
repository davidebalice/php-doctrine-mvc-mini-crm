<?php

namespace App\Controllers;

class HomeController extends RenderController
{
    public function index()
    {
       $data=[
           'title'=>'Homepage',
           'description'=>'Homepage',
       ];
       $this->render('index', $data);
    }
}
