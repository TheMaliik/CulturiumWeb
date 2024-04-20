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
    #[Route('/gallery', name: 'gallery')]
    public function index(): Response
    {
        return $this->render('client_museum/gallery.html.twig');
    }
    #[Route('/gallery1', name: 'gallery1')]
    public function index1(): Response
    {
        return $this->render('client_museum/gallery1.html.twig');
    }
    #[Route('/gallery2', name: 'gallery2')]
    public function index2(): Response
    {
        return $this->render('client_museum/gallery2.html.twig');
    }
    #[Route('/gallery3', name: 'gallery3')]
    public function index3(): Response
    {
        return $this->render('client_museum/gallery3.html.twig');
    }
    #[Route('/gallery4', name: 'gallery4')]
    public function index4(): Response
    {
        return $this->render('client_museum/gallery4.html.twig');
    }
}
