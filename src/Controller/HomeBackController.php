<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class HomeBackController extends AbstractController
{
    #[Route('/admin/adminDashboard', name: 'adminDashboard')]
    public function index(Request $request): Response
    {
        
        
        return $this->render('admin/bars.html.twig');
    }
}
