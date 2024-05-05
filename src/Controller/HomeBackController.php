<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Museum;



class HomeBackController extends AbstractController
{
    #[Route('/admin/adminDashboard', name: 'adminDashboard')]
    public function index(Request $request): Response
    {
        
        
        return $this->render('admin/bars.html.twig');
    }


    #[Route("/RechercheByName", name: "recherche_by_name", methods: ['POST'])]
public function rechercheByName(Request $request): Response
{
    // Récupérer le terme de recherche depuis la requête
    $searchTerm = $request->query->get('search_term');

    // Faites la recherche dans la base de données en utilisant le terme de recherche
    $em = $this->getDoctrine()->getManager();
    $museums = $em->getRepository(Museum::class)->findBy(['name' => $searchTerm]);

    return $this->render('admin_museum/index.html.twig', [
        'museums' => $museums,
        'search_term' => $searchTerm,
    ]);
}

}
