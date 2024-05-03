<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    

    
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('index.htm');
    }
    
    #[Route('/adresseClient', name: 'adresseClient')]
    public function adresseClient(): Response
    {
        return $this->render('adresseClient/adresseClient.html.twig');
    }
    


    
    


}
