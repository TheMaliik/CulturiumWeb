<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('index.htm', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/allposts', name: 'app_post_posts')]
    public function index1(): Response
    {
        return $this->render('addpost/allpost.html.twig');
    }
    #[Route('/allcoms', name: 'app_commentaire_coms')]
    public function index2(): Response
    {
        return $this->render('addcom/allcom.html.twig');
    }
}
