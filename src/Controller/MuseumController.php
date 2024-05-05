<?php

namespace App\Controller;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Museum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MuseumType;

class MuseumController extends AbstractController
{
    #[Route('/museum', name: 'app_museum')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(MuseumType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Traiter les données du formulaire ici
        }

        return $this->render('museum/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}