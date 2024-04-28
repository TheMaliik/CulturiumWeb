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
    #[Route('/events1.1', name: 'events1.1')]
    public function index5(): Response
    {
        
        return $this->render('client_museum/events/events1.1.html.twig');
    }
    #[Route('/events1.2', name: 'events1.2')]
    public function index6(): Response
    {
        return $this->render('client_museum/events/events1.2.html.twig');
    }
    #[Route('/events1.3', name: 'events1.3')]
    public function index7(): Response
    {
        return $this->render('client_museum/events/events1.3.html.twig');
    }
    #[Route('/events2.1', name: 'events2.1')]
    public function index8(): Response
    {
        return $this->render('client_museum/events/events2.1.html.twig');
    }
    #[Route('/events2.2', name: 'events2.2')]
    public function index9(): Response
    {
        return $this->render('client_museum/events/events2.2.html.twig');
    }
    #[Route('/events2.3', name: 'events2.3')]
    public function index10(): Response
    {
        return $this->render('client_museum/events/events2.3.html.twig');
    }
    #[Route('/events3.1', name: 'events3.1')]
    public function index11(): Response
    {
        return $this->render('client_museum/events/events3.1.html.twig');
    }
    #[Route('/events3.2', name: 'events3.2')]
    public function index12(): Response
    {
        return $this->render('client_museum/events/events3.2.html.twig');
    }
    #[Route('/events3.3', name: 'events3.3')]
    public function index13(): Response
    {
        return $this->render('client_museum/events/events3.3.html.twig');
    }
    #[Route('/events4.1', name: 'events4.1')]
    public function index14(): Response
    {
        return $this->render('client_museum/events/events4.1.html.twig');
    }
    #[Route('/events4.2', name: 'events4.2')]
    public function index15(): Response
    {
        return $this->render('client_museum/events/events4.2.html.twig');
    }
    #[Route('/events4.3', name: 'events4.3')]
    public function index16(): Response
    {
        return $this->render('client_museum/events/events4.3.html.twig');
    }
    #[Route('/events5.1', name: 'events5.1')]
    public function index17(): Response
    {
        return $this->render('client_museum/events/events5.1.html.twig');
    }
    #[Route('/events5.2', name: 'events5.2')]
    public function index18(): Response
    {
        return $this->render('client_museum/events/events5.2.html.twig');
    }
    #[Route('/events5.3', name: 'events5.3')]
    public function index19(): Response
    {
        return $this->render('client_museum/events/events5.3.html.twig');
    }

}
