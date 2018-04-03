<?php
// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function home()
    {
        return $this->render('home/home.html.twig');
    }
    
    public function meetings()
    {
        return $this->render('home/meetings.html.twig');
    }
}