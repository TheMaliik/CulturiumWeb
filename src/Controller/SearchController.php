<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_museum_search', methods: ['GET'])]
    public function search(Request $request, MuseumRepository $museumRepository): Response
    {
        $searchTerm = $request->query->get('q');
    
        if ($searchTerm) {
            $results = $museumRepository->findBySearchTerm($searchTerm);
        } else {
            $results = $museumRepository->findAll();
        }
    
        return $this->render('museum/index.html.twig', [
            'museums' => $results,
        ]);
    }
}
