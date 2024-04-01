<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MuseumRepository;

class ClientMuseumController extends AbstractController
{
    
    #[Route("/musees", name:"liste_musees")]
    
    public function listeMusees(MuseumRepository $museumRepository): Response
    {
        // Récupérer la liste des musées depuis le repository
        $museums = $museumRepository->findAll();

        // Passer les données des musées au template pour les afficher
        return $this->render('client_museum/liste_museums.html.twig', [
            'museums' => $museums,
        ]);
    }
}
